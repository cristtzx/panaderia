<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\Receta;
use App\Models\Categoria;
use App\Models\Lote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\ProductoCreadoNotification;
use App\Notifications\ProductoEditadoNotification;

class ProductosController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        $recetas = Receta::all();
        $productos = Productos::with(['categoria', 'receta', 'lotes' => function($query) {
            $query->orderBy('fecha_caducidad', 'asc');
        }])->get();

        return view('modulos.productos.Productos', compact('categorias', 'recetas', 'productos'));
    }

    public function generarCodigo(Request $request)
    {
        $request->validate(['id_categoria' => 'required|exists:categorias,id']);

        $categoria = Categoria::findOrFail($request->id_categoria);
        $prefijo = strtoupper(substr($categoria->nombre, 0, 3));

        $fecha = now();
        $dia = $fecha->format('d');
        $mes = $fecha->format('m');
        $anio = $fecha->format('y');
        $fechaString = $dia . $mes . $anio;

        $codigoBase = "{$prefijo}-{$fechaString}";
        $contador = Productos::where('codigo', 'like', "{$codigoBase}-%")->count();
        $sufijo = str_pad($contador + 1, 2, '0', STR_PAD_LEFT);

        return response()->json(['codigo' => "{$codigoBase}-{$sufijo}"]);
    }

    public function edit($id_producto)
    {
        $producto = Productos::with('lotes')->find($id_producto);
        $categorias = Categoria::all();
        $recetas = Receta::all();
        
        return response()->json([
            'producto' => $producto,
            'categorias' => $categorias,
            'recetas' => $recetas
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'id_categoria' => 'required|exists:categorias,id',
        'id_recetas' => 'required|exists:recetas,id',
        'codigo' => 'required|string|unique:productos,codigo',
        'stock' => 'required|integer|min:0',
        'precio_venta' => 'required|numeric|min:0',
        'precio_estimado' => 'nullable|numeric|min:0',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'agregado' => 'nullable|date',
        'maneja_lotes' => 'sometimes|boolean',
        'dias_caducidad' => 'required_if:maneja_lotes,true|integer|min:1'
    ]);

    // Procesar datos del producto
    $data = $request->except('imagen', 'maneja_lotes', 'dias_caducidad');
    $manejaLotes = $request->has('maneja_lotes') && $request->maneja_lotes == '1';
    $data['maneja_lotes'] = $manejaLotes;

    if ($request->hasFile('imagen')) {
        $path = $request->file('imagen')->store('public/productos');
        $data['imagen'] = str_replace('public/', 'storage/', $path);
    }

    if ($request->agregado) {
        $data['agregado'] = Carbon::parse($request->agregado)->toDateTimeString();
    }

    // Crear el producto
    $producto = Productos::create($data);
    
    // Crear lote inicial si maneja lotes
    if ($manejaLotes && $request->stock > 0) {
        $diasCaducidad = (int)$request->dias_caducidad;
        Lote::create([
            'productos_id' => $producto->id,
            'cantidad' => $request->stock,
            'fecha_entrada' => now()->toDateString(),
            'fecha_caducidad' => now()->addDays($diasCaducidad)->toDateString()
        ]);
    }

   // En ProductosController.php
auth()->user()->notify(new ProductoCreadoNotification($producto, auth()->user()));

    return response()->json([
        'success' => 'Producto creado correctamente',
        'producto' => $producto
    ]);
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id',
            'id_recetas' => 'required|exists:recetas,id',
            'stock' => 'required|integer|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'precio_estimado' => 'nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'maneja_lotes' => 'sometimes|boolean',
            'dias_caducidad' => 'required_if:maneja_lotes,true|integer|min:1'
        ]);

        $producto = Productos::findOrFail($id);
        $stockAnterior = $producto->stock;
        $manejaLotes = $request->has('maneja_lotes') && $request->maneja_lotes == '1';
        
        // Guardar datos originales para comparación
        $originalData = $producto->toArray();
        
        // Actualizar datos del producto
        $data = $request->except('imagen', 'stock', 'maneja_lotes', 'dias_caducidad');
        $data['maneja_lotes'] = $manejaLotes;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen && Storage::exists(str_replace('storage/', 'public/', $producto->imagen))) {
                Storage::delete(str_replace('storage/', 'public/', $producto->imagen));
            }
            
            $path = $request->file('imagen')->store('public/productos');
            $data['imagen'] = str_replace('public/', 'storage/', $path);
        }

        if ($request->agregado) {
            $data['agregado'] = Carbon::parse($request->agregado)->toDateTimeString();
        }

        $producto->update($data);
        
        // Manejo de lotes
        if ($manejaLotes) {
            $diferencia = $request->stock - $stockAnterior;
            
            if ($diferencia > 0) {
                // Agregar nuevo lote con la diferencia
                $diasCaducidad = (int)$request->dias_caducidad;
                Lote::create([
                    'productos_id' => $producto->id,
                    'cantidad' => $diferencia,
                    'fecha_entrada' => now()->toDateString(),
                    'fecha_caducidad' => now()->addDays($diasCaducidad)->toDateString()
                ]);
            } elseif ($diferencia < 0) {
                // Reducir de los lotes existentes
                $this->reducirLotes($producto, abs($diferencia));
            }
        } else {
            // Si desactiva el manejo por lotes, eliminar todos los lotes
            $producto->lotes()->delete();
        }

        // Detectar cambios para la notificación
        $cambios = [];
        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $originalData) && $originalData[$key] != $value) {
                $cambios[] = [
                    'campo' => $key,
                    'de' => $originalData[$key],
                    'a' => $value
                ];
            }
        }

        // Enviar notificación si hubo cambios
        if (!empty($cambios)) {
            $adminUsers = User::where('rol', 'admin')->get();
            foreach ($adminUsers as $admin) {
                $admin->notify(new ProductoEditadoNotification($producto, $cambios, auth()->user()));
            }
        }

        return response()->json([
            'success' => 'Producto actualizado correctamente',
            'producto' => $producto->fresh()
        ]);
    }

    public function destroy($id)
    {
        $producto = Productos::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($producto->imagen && Storage::exists(str_replace('storage/', 'public/', $producto->imagen))) {
            Storage::delete(str_replace('storage/', 'public/', $producto->imagen));
        }
        
        // Eliminar lotes asociados
        $producto->lotes()->delete();
        
        // Eliminar producto
        $producto->delete();

        return response()->json(['success' => 'Producto eliminado correctamente']);
    }

    protected function reducirLotes($producto, $cantidadAReducir)
    {
        $lotes = $producto->lotes()->orderBy('fecha_caducidad')->get();
        
        foreach ($lotes as $lote) {
            if ($cantidadAReducir <= 0) break;
            
            $aReducir = min($cantidadAReducir, $lote->cantidad);
            $lote->decrement('cantidad', $aReducir);
            $cantidadAReducir -= $aReducir;
            
            // Eliminar lote si queda en cero
            if ($lote->cantidad == 0) {
                $lote->delete();
            }
        }
        
        // Si aún queda cantidad por reducir (no había suficiente stock)
        if ($cantidadAReducir > 0) {
            throw new \Exception("No hay suficiente stock para reducir");
        }
    }

    public function generarReporteInventario() {
        $productos = Productos::with('categoria')->get();
        return Pdf::loadView('pdfs.inventario', compact('productos'))
                ->download('inventario_general.pdf');
    }

    public function generarReporteCaducidad() {
        $productos = Productos::whereHas('lotes', function($query) {
            $query->whereDate('fecha_caducidad', '<=', now()->addDays(7));
        })->with(['lotes' => function($query) {
            $query->orderBy('fecha_caducidad');
        }])->get();

        return Pdf::loadView('pdfs.caducidad', compact('productos'))
                ->download('productos_por_caducar.pdf');
    }

    public function generarReporteLotes() {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403, 'Solo administradores pueden ver este reporte');
        }

        $lotes = Lote::with('producto')
                ->orderBy('fecha_entrada', 'desc')
                ->get();

        return Pdf::loadView('pdfs.lotes', compact('lotes'))
                ->download('historial_lotes.pdf');
    }
}