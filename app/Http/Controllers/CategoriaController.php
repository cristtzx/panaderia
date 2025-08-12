<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }




    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('Modulos.Users.Categorias', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias',
            'descripcion' => 'nullable|string'
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,'.$id,
            'descripcion' => 'nullable|string'
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy($id)
    {
        Categoria::destroy($id);
        
        return redirect('categorias')->with('success', 'Categoría eliminada correctamente');
    }
}