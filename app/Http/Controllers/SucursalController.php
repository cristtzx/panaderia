<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }




    public function index()
    {
       

        $sucursales = \App\Models\Sucursal::all(); // Asegúrate que el modelo está bien referenciado
         return view('Modulos.users.Sucursales', compact('sucursales'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
        'telefono' => 'required|string',
        'encargado' => 'required|string|max:255',


      ]);



       Sucursal::create([
        'nombre' => $request->nombre,
        'direccion' => $request->direccion,
        'latitud' => $request->latitud,
        'longitud' => $request->longitud,
        'telefono' => $request->telefono,
        'encargado' => $request->encargado,
        'estado' => 1, // Por defecto, habilitada
        ]);

        return redirect()->route('sucursales.index')->with('success', 'Sucursal registrada correctamente.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Sucursal $sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sucursal $sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'encargado' => 'required|string|max:255',
        ]);

        $sucursal = Sucursal::findOrFail($id);

        $sucursal->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'encargado' => $request->encargado,
        ]);

        return redirect()->route('sucursales.index')->with('success', 'Sucursal actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sucursal = Sucursal::find($id); // ✅ Devuelve un solo modelo

        if (!$sucursal) {
            return redirect()->back()->with('error', 'Sucursal no encontrada.');
        }

        $sucursal->delete();

        return redirect()->route('sucursales.index')->with('success', 'Sucursal eliminada correctamente.');
    }




    public function cambiarEstado(Request $request, Sucursal $sucursal)
    {
        $request->validate(['estado' => 'required|in:0,1']); // Falta punto y coma aquí
        
        $sucursal->update(['estado' => $request->estado]);
        
        return response()->json([ // Falta corchete de apertura
            'message' => $request->estado == 1 
                ? 'Sucursal habilitada correctamente'
                : 'Sucursal desactivada correctamente'
        ]); // Falta paréntesis de cierre
    }

    






}


