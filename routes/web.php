<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\RecetasController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MealDBController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\PlanSemanalController;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    return view('Modulos/Users/Ingresar');
})->name('Ingresar');   


Route::get('Inicio', function () {
    return view('Modulos/Inicio');
});


//Route::get('Primer-usuario',[UsuariosController::class, 'PrimerUsuario']);


Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);



Auth::routes();

Route::get('Sucursales', [SucursalController::class, 'index']); 
Route::get('Ingredientes', [IngredienteController::class, 'index']); 
Route::get('Eliminar-Ingrediente/{id_ingrediente}', [IngredienteController::class, 'destroy']);
Route::post('ingredientes/{id}/update-stock', [IngredienteController::class, 'updateStock']);




Route::get('/pdf-ingredientes', [IngredienteController::class, 'pdf'])->name('ingredientes.pdf');
Route::get('/pdf-ingredientes-minimo', [IngredienteController::class, 'pdfminimo'])->name('ingredientes.pdfminimo');
Route::get('/pdf-ingredientes-maximo', [IngredienteController::class, 'pdfmaximo'])->name('ingredientes.pdfmaximo');



Route::resource('sucursales', SucursalController::class);
Route::resource('ingredientes', IngredienteController::class);




//Usuarios
Route::get('Mis-Datos', function() {

    return view('Modulos.Users.Mis-datos');

});



Route::patch('/sucursales/{sucursal}/estado', [SucursalController::class, 'cambiarEstado'])
    ->name('sucursales.estado');

Route::put('Mis-Datos', [UsuariosController::class, 'ActualizarDatos'])->name('ActualizarDatos');
Route::Get('Usuarios',[UsuariosController::class, 'index']);
Route::post('Usuarios',[UsuariosController::class, 'store']);
Route::get('Cambiar-Estado-Usuario/{id_usuario}/{estado}',[UsuariosController::class, 'CambiarEstado']);
Route::get('Editar-Usuario/{id_usuario}', [UsuariosController::class, 'edit']);
Route::get('Eliminar-Usuario/{id_usuario}',[UsuariosController::class, 'destroy']);
Route::put('Actualizar-Usuario/{id}', [UsuariosController::class, 'update']);



Route::Get('Categorias',[CategoriaController::class, 'index']);
Route::resource('categorias', CategoriaController::class);
Route::get('/Eliminar-Categoria/{id}', [CategoriaController::class, 'destroy']);
Route::get('Recetarios', [RecetasController::class, 'index'])->name('Recetarios');
Route::get('recetas/{receta}/edit', [RecetasController::class, 'edit'])->name('recetas.edit');
Route::put('recetas/{receta}', [RecetasController::class, 'update'])->name('recetas.update');
Route::get('/Eliminar-Receta/{id}', [RecetasController::class, 'destroy'])->name('recetas.eliminar');
Route::resource('recetas', RecetasController::class);


Route::get('/recetas/explorar', function () {
    $userIngredients = Auth::user()->ingredients()->pluck('name')->toArray();
    $userInventory = Auth::user()->inventory()->pluck('name')->toArray();

    return view('modulos.users.recipes', [ // Ajusta la ruta de la vista
        'userIngredients' => $userIngredients,
        'userInventory' => $userInventory
    ]);
})->name('recetas.explorar')->middleware('auth'); // Nombre consistente en español
// routes/api.php
Route::post('/meals/recommend', [MealDBController::class, 'recommend']);

Route::get('/recetas/{id}/pdf', [RecetasController::class, 'generarPdf'])->name('recetas.pdf');
Route::get('/recetas/pdf/todas', [RecetasController::class, 'generarPdfTodas'])->name('recetas.todas.pdf');



//Productos
Route::Get('Productos',[ProductosController::class, 'index']);
Route::post('/generar-codigo-producto', [ProductosController::class, 'generarCodigo'])
     ->name('generar.codigo');

Route::get('Editar-Producto/{id_producto}', [ProductosController::class, 'edit']);
Route::put('Actualizar-Producto/{id}', [ProductosController::class, 'update']);
Route::post('Crear-Producto', [ProductosController::class, 'store']);
Route::get('/Eliminar-Producto/{id}', [ProductosController::class, 'destroy']);

Route::middleware(['auth'])->group(function () {
    // Reportes PDF
    Route::get('/reporte-inventario', [ProductosController::class, 'generarReporteInventario'])->name('reporte.inventario');
    Route::get('/reporte-caducidad', [ProductosController::class, 'generarReporteCaducidad'])->name('reporte.caducidad');
    Route::get('/reporte-lotes', [ProductosController::class, 'generarReporteLotes'])->name('reporte.lotes');
});



Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index')->middleware('auth');
Route::post('/ventas', [VentasController::class, 'store'])->name('ventas.store')->middleware('auth');
Route::get('/ventas/ticket/{venta}', [VentasController::class, 'generarTicket'])->name('ventas.ticket')->middleware('auth');
Route::patch('/ventas/{venta}/cancelar', [VentasController::class, 'cancelar'])->name('ventas.cancelar')->middleware('auth');


// Reemplaza el Route::resource por estas rutas manuales
Route::get('/plan-semanal', [PlanSemanalController::class, 'index'])->name('plan-semanal.index');
Route::post('/plan-semanal', [PlanSemanalController::class, 'store'])->name('plan-semanal.store');
Route::put('/plan-semanal/{plan}', [PlanSemanalController::class, 'update'])->name('plan-semanal.update');
Route::delete('/plan-semanal/{plan}', [PlanSemanalController::class, 'destroy'])->name('plan-semanal.destroy');

// Rutas para PDFs
Route::get('/descargar/plan-semanal', [PlanSemanalController::class, 'pdfSemanaActual'])
     ->name('pdf.plan-semanal');

Route::get('/descargar/historial', [PlanSemanalController::class, 'pdfHistorial'])
     ->name('pdf.historial');      





Route::get('/Inicio', [HomeController::class, 'index'])->name('inicio');
