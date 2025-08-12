<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVentas;
use App\Models\Sucursal;
use App\Models\Productos;
use App\Models\MotivoCancelacion;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motivosCancelacion = MotivoCancelacion::all();
        $sucursales = Sucursal::all();
        $productos = Productos::all();
        $ventas = Venta::with(['sucursal', 'user', 'motivo_cancelacion'])
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return view('Modulos.ventas.ventas', compact('ventas', 'sucursales', 'motivosCancelacion', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sucursales_id' => 'required|exists:sucursales,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,terminal,transferencia',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'efectivo_recibido' => 'required_if:metodo_pago,efectivo|numeric|min:0',
            'referencia_pago' => 'required_if:metodo_pago,terminal|nullable|string|max:50',
            'codigo_transferencia' => 'required_if:metodo_pago,tarjeta|nullable|string|max:50',
            'token' => 'required_if:metodo_pago,tarjeta|nullable|string',
            'payment_method_id' => 'required_if:metodo_pago,tarjeta|nullable|string',
            'installments' => 'required_if:metodo_pago,tarjeta|nullable|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $total = collect($request->productos)->sum(function($producto) {
                return $producto['cantidad'] * $producto['precio_unitario'];
            });

            $venta = Venta::create([
                'sucursales_id' => $request->sucursales_id,
                'user_id' => auth()->id(),
                'total' => $total,
                'metodo_pago' => $request->metodo_pago,
                'referencia_pago' => $request->referencia_pago ?? null,
                'codigo_transferencia' => $request->codigo_transferencia ?? null,
                'efectivo_recibido' => $request->efectivo_recibido ?? null,
                'cambio' => $request->metodo_pago == 'efectivo' ? ($request->efectivo_recibido - $total) : null,
                'estado' => 'completada'
            ]);

            if (in_array($request->metodo_pago, ['terminal', 'transferencia', 'tarjeta'])) {
                $payment = $this->createMercadoPagoPayment(
                    $venta, 
                    $request->metodo_pago,
                    $request->metodo_pago == 'terminal' ? $request->referencia_pago : null
                );
                
                $venta->update([
                    'mercado_pago_id' => $payment->id,
                    'estado_pago' => $payment->status,
                    'metodo_pago_detalle' => $payment->payment_method_id,
                    'codigo_transferencia' => $payment->id
                ]);
            }

            foreach ($request->productos as $producto) {
                DetalleVentas::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'subtotal' => $producto['cantidad'] * $producto['precio_unitario']
                ]);

                Productos::where('id', $producto['id'])->decrement('stock', $producto['cantidad']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada correctamente',
                'venta_id' => $venta->id,
                'ticket_url' => route('ventas.ticket', ['venta' => $venta->id])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate ticket for a sale
     */
    public function generarTicket(Venta $venta)
    {
        $detalles = DetalleVentas::where('venta_id', $venta->id)
                      ->with('producto')
                      ->get();

        return view('modulos.ventas.ticket', [
            'venta' => $venta,
            'detalles' => $detalles,
            'sucursal' => $venta->sucursal,
            'usuario' => auth()->user()
        ]);
    }

    /**
     * Cancel a sale
     */
    public function cancelar(Request $request, Venta $venta)
{
    $request->validate([
        'motivo_cancelacion_id' => 'required|exists:motivos_cancelacion,id',
        'motivo_adicional' => 'nullable|string|max:500'
    ]);

    DB::beginTransaction();

    try {
        // Revertir stock
        $detalles = DetalleVentas::where('venta_id', $venta->id)->get();
        
        foreach ($detalles as $detalle) {
            Productos::where('id', $detalle->producto_id)
                     ->increment('stock', $detalle->cantidad);
        }

        // Actualizar estado de la venta
        $updateData = [
            'estado' => 'cancelada',
            'motivo_cancelacion_id' => $request->motivo_cancelacion_id
        ];

        // Si hay motivo adicional, guardarlo
        if ($request->motivo_adicional) {
            $updateData['motivo_adicional'] = $request->motivo_adicional;
        }

        $venta->update($updateData);

        DB::commit();

        return redirect()->route('ventas.index')
                         ->with('success', 'Venta cancelada correctamente');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
    }
}

    /**
     * Test Mercado Pago connection
     */
    public function pruebaConexion()
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
        $client = new PaymentClient();
        
        try {
            $methods = $client->get('/v1/payment_methods');
            return response()->json($methods);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'token_used' => config('services.mercadopago.access_token'),
            ], 500);
        }
    }

    /**
     * Verify Mercado Pago payment
     */
    public function verificarPago(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|string'
        ]);

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
        $client = new PaymentClient();

        try {
            $payment = $client->get($request->payment_id);
            
            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'status' => $payment->status,
                    'amount' => $payment->transaction_amount,
                    'method' => $payment->payment_method_id,
                    'date' => $payment->date_created
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Setup Mercado Pago configuration
     */
    private function setupMercadoPago()
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
    }

    /**
     * Create Mercado Pago payment
     */
    private function createMercadoPagoPayment($venta, $metodoPago, $referencia = null)
    {
        $this->setupMercadoPago();
        $client = new PaymentClient();

        try {
            $paymentData = [
                'transaction_amount' => (float)$venta->total,
                'description' => "Venta #{$venta->id}",
                'payment_method_id' => $this->mapPaymentMethod($metodoPago),
                'external_reference' => (string)$venta->id,
                'payer' => [
                    'email' => auth()->user()->email
                ],
                'binary_mode' => true
            ];

            if ($metodoPago === 'terminal' && $referencia) {
                $paymentData['point_of_interaction'] = [
                    'type' => 'CHECKOUT',
                    'linked_to' => $referencia
                ];
            }

            if ($metodoPago === 'tarjeta' && request()->has('token')) {
                $paymentData['token'] = request()->input('token');
                $paymentData['installments'] = (int)request()->input('installments', 1);
            }

            return $client->create($paymentData);

        } catch (MPApiException $e) {
            \Log::error('Error en API MercadoPago: '.$e->getApiResponse()->getContent());
            throw new \Exception("Error al procesar pago: ".$e->getMessage());
        }
    }

    /**
     * Map payment method to Mercado Pago's format
     */
    private function mapPaymentMethod($metodo)
    {
        return match($metodo) {
            'terminal' => 'account_money',
            'tarjeta' => request()->input('payment_method_id', 'visa'),
            'transferencia' => 'bank_transfer',
            default => 'bank_transfer'
        };
    }
}