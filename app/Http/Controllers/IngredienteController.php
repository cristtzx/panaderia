<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\Medida;
use App\Models\NotificacionIngrediente;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionIgredienteMail;

class IngredienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Ingrediente::with('medida');
        
        if ($request->has('nombre')) {
            $query->where('Nombre', 'like', '%'.$request->input('nombre').'%');
        }
        
        if ($request->has('tipo_stock')) {
            $tipo = $request->input('tipo_stock');
            if ($tipo === 'minimo') {
                $query->whereColumn('Stock', '<', 'Stock_minimo');
            } elseif ($tipo === 'maximo') {
                $query->whereColumn('Stock', '>', 'Stock_maximo');
            } elseif ($tipo === 'normal') {
                $query->whereColumn('Stock', '>=', 'Stock_minimo')
                      ->whereColumn('Stock', '<=', 'Stock_maximo');
            }
        }

        $ingredientes = $query->get();
        $medidas = Medida::all();
        return view('Modulos.users.Ingredientes', compact('ingredientes', 'medidas')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Nombre' => 'required|string|max:100',
            'Stock' => 'required|numeric|min:0',
            'Stock_maximo' => 'required|numeric|min:0',
            'Stock_minimo' => 'required|numeric|min:0',
            'id_medidas' => 'required|exists:medidas,id'
        ]);

        try {
            Ingrediente::create([
                'Nombre' => $validated['Nombre'],
                'Stock' => $validated['Stock'],
                'Stock_maximo' => $validated['Stock_maximo'],
                'Stock_minimo' => $validated['Stock_minimo'],
                'id_medidas' => $validated['id_medidas']
            ]);
            
            return redirect()->route('ingredientes.index')
                ->with('success', 'Ingrediente creado exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear ingrediente: '.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Nombre' => 'sometimes|string|max:100',
            'Stock' => 'sometimes|numeric|min:0',
            'Stock_maximo' => 'sometimes|numeric|min:0',
            'Stock_minimo' => 'sometimes|numeric|min:0',
            'id_medidas' => 'sometimes|exists:medidas,id'
        ]);

        try {
            $ingrediente = Ingrediente::findOrFail($id);
            $ingrediente->update($validated);

            $notificacion = $ingrediente->notificacion;

            if (
                $notificacion &&
                $notificacion->activo &&
                isset($validated['Stock']) &&
                $validated['Stock'] < $notificacion->umbral
            ) {
                $mensaje = str_replace(
                    ['{ingrediente}', '{umbral}', '{medida}'],
                    [
                        $ingrediente->Nombre, 
                        $notificacion->umbral, 
                        $ingrediente->medida ? $ingrediente->medida->nombre : ''
                    ],
                    $notificacion->mensaje_personalizado
                );

                $destinatarios = array_map('trim', explode(',', $notificacion->destinatarios));
                Mail::to($destinatarios)->send(new NotificacionIngredienteMail($mensaje));

                $notificacion->update([
                    'ultimo_envio' => now(),
                    'veces_enviado' => $notificacion->veces_enviado + 1
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ingrediente actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: '.$e->getMessage()
            ], 500);
        }
    }
    

    public function pdf()
    {
        $ingredientes = Ingrediente::with('medida')->get();
        $pdf = Pdf::loadView('pdfs.ingredientepdf', compact('ingredientes'));
        return $pdf->stream('ingredientes_completos.pdf');
    }

    public function pdfminimo()
    {
        $ingredientes = Ingrediente::with('medida')
            ->whereColumn('Stock', '<', 'Stock_minimo')
            ->get();
        $pdf = Pdf::loadView('pdfs.ingrediente-minimo', compact('ingredientes'));
        return $pdf->stream('ingredientes_stock_minimo.pdf');
    }

    public function pdfmaximo()
    {
        $ingredientes = Ingrediente::with('medida')
            ->whereColumn('Stock', '>', 'Stock_maximo')
            ->get();
        $pdf = Pdf::loadView('pdfs.pdf-maximo', compact('ingredientes'));
        return $pdf->stream('ingredientes_stock_maximo.pdf');
    }




        public function destroy($id_ingrediente)
    {
        $ingrediente = Ingrediente::find($id_ingrediente);
        Ingrediente::destroy($id_ingrediente);

        return redirect('Ingredientes'); // Redirección simple sin mensaje
    }

 
        public function updateStock(Request $request, $id)
    {
        $request->validate([
            'Stock' => 'required|numeric|min:0'
        ]);

        try {
            $ingrediente = Ingrediente::findOrFail($id);
            $ingrediente->update(['Stock' => $request->Stock]);
            
            return response()->json([
                'success' => true,
                'message' => 'Stock actualizado correctamente',
                'new_stock' => $ingrediente->Stock
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: '.$e->getMessage()
            ], 500);
        }
    }




}