<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Productos;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $mes  = now()->month;
        $anio = now()->year;

        // ====== KPIs ======
        $totalVentas      = Venta::count();
        $gananciasTotales = Venta::sum('total');

        // ====== Config ======
        $umbral        = (int) config('pan.inventario_minimo', 10);
        $critico       = (int) config('pan.stock_critico', 3);
        $caducaEnDias  = (int) config('pan.caduca_en_dias', 7);

        // ====== Inventario bajo ======
        $inventarioBajo = Productos::select('id', 'nombre', 'stock', 'codigo')
            ->where('stock', '>', 0)
            ->where('stock', '<=', $umbral)
            ->orderBy('stock', 'asc')
            ->get();

        // ====== Alertas stock ======
        $productosCriticos = Productos::where('stock', '>', 0)
            ->where('stock', '<=', $critico)
            ->orderBy('stock', 'asc')
            ->get();

        $productosSinStock = Productos::where('stock', 0)
            ->orderBy('nombre')
            ->get();

        // ====== Por caducar (desde LOTES) ======
        $hoy    = Carbon::today();
        $limite = $hoy->copy()->addDays($caducaEnDias);

        $productosPorCaducar = DB::table('lotes')
            ->join('productos', 'productos.id', '=', 'lotes.productos_id')
            ->whereNotNull('lotes.fecha_caducidad')
            ->whereBetween('lotes.fecha_caducidad', [$hoy, $limite])
            ->where('lotes.cantidad', '>', 0)
            ->select([
                'lotes.id as lote_id',
                'productos.id as producto_id',
                'productos.nombre',
                'lotes.cantidad',
                'lotes.fecha_entrada',
                'lotes.fecha_caducidad',
            ])
            ->orderBy('lotes.fecha_caducidad', 'asc')
            ->get()
            ->map(function ($row) {
                $row->dias_restantes = Carbon::today()->diffInDays(Carbon::parse($row->fecha_caducidad), false);
                return $row;
            });

        // ====== Ventas por mes (para barra mensual) ======
        $ventasMensuales = Venta::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('SUM(total) as total')
        )->groupBy('mes')->get()->pluck('total', 'mes');

        $ventasFormateadas = array_fill(1, 12, 0);
        foreach ($ventasMensuales as $m => $total) {
            $ventasFormateadas[$m] = (float) $total;
        }

        // ====== Top productos (usa el nombre de TU columna: productos_id o producto_id) ======
        // Si tu columna es productos_id (común): usa esta línea
        $detalleProductoFk = 'detalle_ventas.productos_id';
        // Si en tu BD es producto_id, cambia la de arriba por: $detalleProductoFk = 'detalle_ventas.producto_id';

   $topProductos = DB::table('detalle_ventas')
    ->join('ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
    ->join('productos', 'productos.id', '=', 'detalle_ventas.producto_id') // 👈 aquí el cambio
    ->whereMonth('ventas.created_at', $mes)
    ->whereYear('ventas.created_at', $anio)
    ->select('productos.nombre', DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'))
    ->groupBy('productos.nombre')
    ->orderByDesc('total_vendido')
    ->limit(5)
    ->get();

$topProductosLabels = $topProductos->pluck('nombre');
$topProductosData   = $topProductos->pluck('total_vendido');

        $topProductosLabels = $topProductos->pluck('nombre');
        $topProductosData   = $topProductos->pluck('total_vendido');

        // ====== Métodos de pago (mes actual) ======
        $metodosPagoMes = Venta::select('metodo_pago', DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->groupBy('metodo_pago')
            ->get();

        $metodosPagoLabels = $metodosPagoMes->pluck('metodo_pago');
        $metodosPagoData   = $metodosPagoMes->pluck('total');

        // ====== Ventas por sucursal (mes actual) ======
        $ventasPorSucursal = DB::table('ventas')
            ->join('sucursales', 'sucursales.id', '=', 'ventas.sucursales_id')
            ->whereMonth('ventas.created_at', $mes)
            ->whereYear('ventas.created_at', $anio)
            ->select('sucursales.nombre', DB::raw('SUM(ventas.total) as total'))
            ->groupBy('sucursales.nombre')
            ->orderByDesc('total')
            ->get();

        $sucLabels = $ventasPorSucursal->pluck('nombre');
        $sucData   = $ventasPorSucursal->pluck('total');

        // ====== Últimas ventas ======
        $ultimasVentas = Venta::with(['user', 'sucursal'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('modulos.Inicio', compact(
            'totalVentas',
            'gananciasTotales',
            'ventasFormateadas',
            // gráficos: variables que la VISTA espera
            'topProductosLabels',
            'topProductosData',
            'metodosPagoLabels',
            'metodosPagoData',
            'sucLabels',
            'sucData',
            // tablas/listas
            'ultimasVentas',
            'productosPorCaducar',
            'inventarioBajo',
            'productosCriticos',
            'productosSinStock',
            // config
            'caducaEnDias',
            'umbral',
            'critico'
        ));
    }
}
