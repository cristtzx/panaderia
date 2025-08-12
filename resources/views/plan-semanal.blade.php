@extends('welcome')

@section('contenido')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="text-center"><i class="fas fa-calendar-alt mr-2 text-primary"></i>Planificación Semanal de Recetas</h1>
                </div>
            </div>
        </div>
    </section>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <section class="content">
        <div class="container-fluid">
            <!-- Controles de navegación semanal -->
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <a href="?week={{ $startOfWeek->copy()->subWeek()->format('Y-m-d') }}" 
                       class="btn btn-secondary mr-2">
                        <i class="fas fa-chevron-left"></i> Semana Anterior
                    </a>
                    
                    <span class="mx-3 font-weight-bold">
                        Semana del {{ $startOfWeek->format('d/m/Y') }} al {{ $endOfWeek->format('d/m/Y') }}
                    </span>
                    
                    <a href="?week={{ $startOfWeek->copy()->addWeek()->format('Y-m-d') }}" 
                       class="btn btn-secondary ml-2">
                        Semana Siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <!-- Botones de acciones -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <div class="btn-group" role="group">
                            <!-- Botón para descargar PDF -->
                            <a href="{{ route('pdf.plan-semanal') }}" 
                               class="btn btn-danger btn-lg mr-2"
                               target="_blank"
                               onclick="event.preventDefault(); window.open(this.href);">
                               <i class="fas fa-file-pdf mr-2"></i> Descargar Plan Semanal
                            </a>
                            
                            <!-- Botón para historial -->
                            <a href="{{ route('pdf.historial') }}" 
                               class="btn btn-secondary btn-lg"
                               target="_blank"
                               onclick="event.preventDefault(); window.open(this.href);">
                               <i class="fas fa-history mr-2"></i> Descargar Historial
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de agregar receta -->
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title mb-0"><i class="fas fa-plus-circle mr-2"></i>Agregar Receta al Plan</h3>
                        </div>
                        <div class="card-body">
                            <form id="form-plan" action="{{ route('plan-semanal.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label><i class="fas fa-utensils mr-1"></i> Receta</label>
                                        <select class="form-control select2" name="recetas_id" required>
                                            <option value="">Seleccionar receta...</option>
                                            @foreach($recetas as $receta)
                                                <option value="{{ $receta->id }}">{{ $receta->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label><i class="far fa-calendar-alt mr-1"></i> Fecha</label>
                                        <input type="date" class="form-control" name="day" required 
                                               min="{{ $startOfWeek->format('Y-m-d') }}"
                                               max="{{ $endOfWeek->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label><i class="fas fa-boxes mr-1"></i> Unidades</label>
                                        <input type="number" class="form-control" name="unidades_esperadas" min="1" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label><i class="far fa-sticky-note mr-1"></i> Notas</label>
                                        <input type="text" class="form-control" name="notas" placeholder="Opcional">
                                    </div>
                                    <div class="col-md-1 form-group d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de planificación -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title mb-0"><i class="fas fa-clipboard-list mr-2"></i>Planificación Semanal</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" style="width: 12%"><i class="far fa-calendar mr-1"></i> Día</th>
                                            <th style="width: 20%"><i class="fas fa-utensils mr-1"></i> Receta</th>
                                            <th class="text-center" style="width: 12%"><i class="fas fa-clipboard-check mr-1"></i> Esperadas</th>
                                            <th class="text-center" style="width: 12%"><i class="fas fa-clipboard mr-1"></i> Reales</th>
                                            <th style="width: 15%"><i class="fas fa-sliders-h mr-1"></i> Ajustes</th>
                                            <th style="width: 15%"><i class="far fa-comment mr-1"></i> Notas</th>
                                            <th class="text-center" style="width: 14%"><i class="fas fa-cogs mr-1"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $diasSemana = [
                                                'Monday' => 'Lunes',
                                                'Tuesday' => 'Martes',
                                                'Wednesday' => 'Miércoles',
                                                'Thursday' => 'Jueves',
                                                'Friday' => 'Viernes',
                                                'Saturday' => 'Sábado',
                                                'Sunday' => 'Domingo'
                                            ];
                                            
                                            $currentDay = $startOfWeek->copy();
                                            while ($currentDay <= $endOfWeek) {
                                                $nombreDia = $diasSemana[$currentDay->format('l')];
                                                $formattedDate = $currentDay->format('d/m/Y');
                                                $plansForDay = $weeklyPlans->filter(function($item) use ($currentDay) {
                                                    return Carbon\Carbon::parse($item->day)->isSameDay($currentDay);
                                                });
                                        @endphp
                                        
                                        <tr class="bg-light-blue">
                                            <td colspan="7" class="font-weight-bold">
                                                <i class="far fa-calendar-check mr-2"></i>{{ $nombreDia }} ({{ $formattedDate }})
                                            </td>
                                        </tr>
                                        
                                        @if($plansForDay->count())
                                            @foreach($plansForDay as $plan)
                                            <tr>
                                                <td></td>
                                                <td class="font-weight-bold">{{ $plan->receta->nombre }}</td>
                                                <td class="text-center">{{ $plan->unidades_esperadas }}</td>
                                                <td class="text-center">
                                                    <input type="number" class="form-control form-control-sm text-center" 
                                                           name="unidades_reales" value="{{ $plan->unidades_reales ?? '' }}" 
                                                           form="form-edit-{{ $plan->id }}" 
                                                           {{ $plan->unidades_reales ? 'disabled' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" 
                                                           name="ajustes" value="{{ $plan->ajustes ?? '' }}" 
                                                           form="form-edit-{{ $plan->id }}"
                                                           {{ $plan->ajustes ? 'disabled' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" 
                                                           name="notas" value="{{ $plan->notas ?? '' }}" 
                                                           form="form-edit-{{ $plan->id }}"
                                                           {{ $plan->notas ? 'disabled' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <form id="form-edit-{{ $plan->id }}" action="{{ route('plan-semanal.update', $plan->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Guardar cambios"
                                                            {{ $plan->unidades_reales ? 'disabled' : '' }}>
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('plan-semanal.destroy', $plan->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-3">
                                                    <i class="far fa-calendar-times fa-lg mb-2"></i><br>
                                                    No hay recetas programadas para este día
                                                </td>
                                            </tr>
                                        @endif
                                        
                                        @php
                                            $currentDay->addDay();
                                            }
                                        @endphp
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('css')
<style>
    .bg-light-blue {
        background-color: #e3f2fd !important;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .form-control-sm {
        height: calc(1.8125rem + 2px);
        padding: 0.25rem 0.5rem;
    }
    .form-control:disabled {
        background-color: #f8f9fa !important;
        border-color: #e9ecef !important;
    }
    .btn:disabled {
        opacity: 0.65;
    }
    /* Estilos para los botones de acciones */
    .btn-group .btn {
        border-radius: 0.375rem;
        transition: all 0.3s ease;
    }
    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .btn-group .btn:first-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .btn-group .btn:last-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .btn-group .btn:not(:first-child):not(:last-child) {
        border-radius: 0;
    }
</style>
@endpush

@push('js')
<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inicializar select2
    $('.select2').select2({
        placeholder: "Seleccionar receta",
        allowClear: true
    });

    // Confirmar eliminación con SweetAlert
    $('form[action*="destroy"]').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: '¿Eliminar registro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
    // Confirmar guardado con SweetAlert
    $('form[id^="form-edit-"]').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formId = form.id;
        
        Swal.fire({
            title: '¿Guardar cambios?',
            text: "¿Deseas guardar los cambios realizados?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Deshabilitar campos antes de enviar
                $(`#${formId} input`).prop('disabled', true);
                $(`#${formId} button[type="submit"]`).prop('disabled', true);
                
                // Enviar formulario
                form.submit();
            }
        });
    });
    
    // Mostrar mensajes de sesión con SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'Entendido'
        });
    @endif
});
</script>
@endpush