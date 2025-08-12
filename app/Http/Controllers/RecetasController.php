<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;
use App\Models\IngredienteReceta;
use App\Models\Receta;
use Barryvdh\DomPDF\Facade\Pdf;

class RecetasController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }




    
    public function index()
    {
        $recetas = Receta::with('ingredientes')->get();
        $ingredientes = Ingrediente::all();
        
        return view('modulos.users.Recetarios', compact('recetas', 'ingredientes'));
    }




    public function create()
    {
        
    }


     public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tiempo_preparacion' => 'required|integer|min:1',
            'instrucciones' => 'required|string',
            'ingredientes' => 'required|array|min:1',
            'ingredientes.*.id' => 'required|exists:ingredientes,id',
            'ingredientes.*.cantidad' => 'required|numeric|min:0.1',
            'ingredientes.*.unidad' => 'required|string|max:50'
        ], [
            'ingredientes.required' => 'Debe agregar al menos un ingrediente',
            'ingredientes.*.id.required' => 'Seleccione un ingrediente',
            'ingredientes.*.cantidad.required' => 'Ingrese la cantidad',
            'ingredientes.*.unidad.required' => 'Seleccione la unidad'
        ]);

        // Crear la receta
        $receta = Receta::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'tiempo_preparacion' => $validated['tiempo_preparacion'],
            'instrucciones' => $validated['instrucciones']
        ]);

        // Preparar datos para la relación muchos-a-muchos
        $ingredientesData = [];
        foreach ($validated['ingredientes'] as $ingrediente) {
            $ingredientesData[$ingrediente['id']] = [
                'cantidad' => $ingrediente['cantidad'],
                'unidad' => $ingrediente['unidad']
            ];
        }

        // Sincronizar ingredientes
        $receta->ingredientes()->sync($ingredientesData);

        return redirect()->route('recetas.index')->with('success', 'Receta creada exitosamente');
    }



    public function show(string $id)
    {
        
    }

public function edit($id)
{
    $receta = Receta::with('ingredientes')->findOrFail($id);
    $all_ingredientes = Ingrediente::all();
    
    return response()->json([
        'success' => true,
        'data' => [
            'receta' => [
                'id' => $receta->id,
                'nombre' => $receta->nombre,
                'descripcion' => $receta->descripcion,
                'instrucciones' => $receta->instrucciones,
                'tiempo_preparacion' => $receta->tiempo_preparacion
            ],
            'ingredientes' => $receta->ingredientes->map(function($ing) {
                return [
                    'id' => $ing->id,
                    'nombre' => $ing->Nombre,
                    'cantidad' => $ing->pivot->cantidad,
                    'unidad' => $ing->pivot->unidad
                ];
            }),
            'all_ingredientes' => $all_ingredientes->map(function($ing) {
                return [
                    'id' => $ing->id,
                    'nombre' => $ing->Nombre
                ];
            })
        ]
    ]);
}

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'instrucciones' => 'required|string',
            'tiempo_preparacion' => 'required|integer|min:1',
            'ingredientes' => 'required|array|min:1',
            'ingredientes.*.id' => 'required|exists:ingredientes,id',
            'ingredientes.*.cantidad' => 'required|numeric|min:0.1',
            'ingredientes.*.unidad' => 'required|in:g,kg,ml,l,taza,cda,cdita,pizca,unidades,hojas,dientes,rebanadas'
        ]);

        $receta = Receta::findOrFail($id);
        $receta->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'instrucciones' => $validated['instrucciones'],
            'tiempo_preparacion' => $validated['tiempo_preparacion']
        ]);

        $ingredientesData = [];
        foreach ($validated['ingredientes'] as $ingrediente) {
            $ingredientesData[$ingrediente['id']] = [
                'cantidad' => $ingrediente['cantidad'],
                'unidad' => $ingrediente['unidad']
            ];
        }

        $receta->ingredientes()->sync($ingredientesData);

        return redirect()->route('recetas.index')->with('success', 'Receta actualizada exitosamente');
    }







    public function destroy($id)
 {
    try {
        $receta = Receta::findOrFail($id);
        
        // Eliminar solo las relaciones en la tabla pivote (sin afectar ingredientes)
        $receta->ingredientes()->detach();
        
        // Eliminar la receta
        $receta->delete();

        return back()->with('success', 'Receta eliminada exitosamente');
                       
    } catch (\Exception $e) {
        return back()->with('error', 'Error al eliminar la receta: '.$e->getMessage());
    }
 }
 public function generarPdf($id)
{
    $receta = Receta::with('ingredientes')->findOrFail($id);
    $pdf = PDF::loadView('pdfs.pdfreceta', compact('receta'));
    
    // Para previsualizar en el navegador:
    return $pdf->stream('receta_'.$receta->nombre.'.pdf');
    
    // Para forzar descarga (como lo tienes actualmente):
    // return $pdf->download('receta_'.$receta->nombre.'.pdf');
}

public function generarPdfTodas()
{
    $recetas = Receta::with('ingredientes')->get();
    $pdf = PDF::loadView('pdfs.todas', compact('recetas'));
    
    // Opción 1: Para abrir en nueva pestaña/navegador
    return $pdf->stream('todas_las_recetas.pdf');
    
    // Opción 2: Alternativa con headers personalizados
    // return response($pdf->output(), 200)
    //     ->header('Content-Type', 'application/pdf')
    //     ->header('Content-Disposition', 'inline; filename="todas_las_recetas.pdf"');
}



}
