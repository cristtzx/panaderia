@extends('welcome')

@section('contenido')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Planificación Semana {{ $week }} del {{ $year }}
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('plan-semanal.pdf', ['week' => "{$year}-W{$week}"]) }}" 
                       class="btn btn-danger">
                        <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                    </a>
                    <a href="{{ route('plan-semanal.history') }}" 
                       class="btn btn-default">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al historial
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Del {{ $startOfWeek->format('d/m/Y') }} al {{ $endOfWeek->format('d/m/Y') }}
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 15%">Día</th>
                                    <th style="width: 25%">Receta</th>
                                    <th style="width: 10%" class="text-center">Unidades Esperadas</th>
                                    <th style="width: 10%" class="text-center">Unidades Reales</th>
                                    <th style="width: 20%">Notas</th>
                                    <th style="width: 20%">Ajustes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentDay = $startOfWeek->copy();
                                    $totalEsperadas = 0;
                                    $totalReales = 0;
                                @endphp
                                
                                @while($currentDay <= $endOfWeek)
                                    @php
                                        $formattedDate = $currentDay->format('d/m/Y');
                                        $dayPlans = $plans[$currentDay->format('Y-m-d')] ?? [];
                                    @endphp
                                    
                                    @if(count($dayPlans) > 0)
                                        @foreach($dayPlans as $index => $plan)
                                            @php
                                                $totalEsperadas += $plan->unidades_esperadas;
                                                $totalReales += $plan->unidades_reales ?? 0;
                                            @endphp
                                            <tr>
                                                @if($index === 0)
                                                    <td rowspan="{{ count($dayPlans) }}" class="align-middle">
                                                        {{ $currentDay->isoFormat('dddd') }}<br>
                                                        <small>{{ $formattedDate }}</small>
                                                    </td>
                                                @endif
                                                <td>{{ $plan->receta->nombre }}</td>
                                                <td class="text-center">{{ $plan->unidades_esperadas }}</td>
                                                <td class="text-center">{{ $plan->unidades_reales ?? '-' }}</td>
                                                <td>{{ $plan->notas ?? '-' }}</td>
                                                <td>{{ $plan->ajustes ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{ $currentDay->isoFormat('dddd') }}<br><small>{{ $formattedDate }}</small></td>
                                            <td colspan="5" class="text-center text-muted">No hay recetas programadas</td>
                                        </tr>
                                    @endif
                                    
                                    @php $currentDay->addDay(); @endphp
                                @endwhile
                                
                                <tr class="bg-light">
                                    <td colspan="2" class="text-right"><strong>Totales:</strong></td>
                                    <td class="text-center"><strong>{{ $totalEsperadas }}</strong></td>
                                    <td class="text-center"><strong>{{ $totalReales }}</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection