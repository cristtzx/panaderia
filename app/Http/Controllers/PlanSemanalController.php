<?php


namespace App\Http\Controllers;

use App\Models\WeeklyPlan;
use App\Models\Receta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // Asegúrate de tener esta línea al inicio
use App\Notifications\WeeklyPlanCreated;


class PlanSemanalController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
{
    // Obtener el rango de fechas de la semana actual
    $startOfWeek = now()->startOfWeek(); // Lunes
    $endOfWeek = now()->endOfWeek();     // Domingo

    // Obtener planes de la semana actual con la relación receta
    $weeklyPlans = WeeklyPlan::with('receta')
        ->whereBetween('day', [$startOfWeek, $endOfWeek])
        ->orderBy('day')
        ->get();

    $recetas = Receta::all();
    
    return view('plan-semanal', compact('weeklyPlans', 'recetas', 'startOfWeek', 'endOfWeek'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'recetas_id' => 'required|exists:recetas,id',
        'day' => 'required|date',
        'unidades_esperadas' => 'required|integer|min:1',
        'notas' => 'nullable|string|max:255'
    ]);

    // Crear el plan semanal
    $plan = WeeklyPlan::create($validated);

    // Obtener el usuario autenticado (o el que debe recibir la notificación)
    $user = auth()->user();

    // Enviar notificación por correo
    $user->notify(new WeeklyPlanCreated($plan));

    return redirect()->route('plan-semanal.index')
        ->with('success', 'Receta agregada al plan semanal y notificación enviada');
}


    public function update(Request $request, $id)
{
    $plan = WeeklyPlan::findOrFail($id);
    
    $validated = $request->validate([
        'unidades_reales' => 'nullable|integer|min:0',
        'ajustes' => 'nullable|string|max:255',
        'notas' => 'nullable|string|max:255'
    ]);
    
    $plan->update($validated);
    
    return redirect()->route('plan-semanal.index')
        ->with('success', 'Los cambios se guardaron correctamente');
}




    public function destroy(WeeklyPlan $planSemanal)
    {
        $planSemanal->delete();
        return redirect()->route('plan-semanal.index')->with('success', 'Receta eliminada del plan semanal');
 
 
    }




public function pdfSemanaActual()
{
    $inicio = Carbon::now()->startOfWeek();
    $fin = Carbon::now()->endOfWeek();
    
    $planes = WeeklyPlan::with('receta')
        ->whereBetween('day', [$inicio, $fin])
        ->orderBy('day')
        ->get()
        ->groupBy(function($item) {
            // Convierte el string a fecha primero
            return Carbon::parse($item->day)->format('Y-m-d');
        });
        
    return PDF::loadView('pdfs.semana-actual', [
        'planes' => $planes,
        'inicio' => $inicio,
        'fin' => $fin
    ])->download('plan-semanal-actual.pdf');
}

public function pdfHistorial()
{
    $semanas = WeeklyPlan::selectRaw('YEAR(day) as año, WEEK(day, 1) as semana')
        ->groupBy('año', 'semana')
        ->orderBy('año', 'desc')
        ->orderBy('semana', 'desc')
        ->get()
        ->map(function($item) {
            // Convertir a objeto Carbon para la vista
            $item->inicio_semana = Carbon::now()
                ->setISODate($item->año, $item->semana)
                ->startOfWeek();
            $item->fin_semana = $item->inicio_semana->copy()->endOfWeek();
            return $item;
        });
        
    return PDF::loadView('pdfs.historial', [
        'semanas' => $semanas
    ])->download('historial-planificacion.pdf');
}
}