@extends('welcome')

@section('contenido')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="text-primary" style="font-weight: 600;">
                        <i class="fa fa-utensils" style="color: #3c8dbc; margin-right: 10px;"></i> Gestión de Ingredientes
                    </h1>
                </div>
                <div class="col-md-4 text-right">
                    <span class="badge bg-blue" style="font-size: 16px; padding: 8px 15px;">
                        <i class="fa fa-info-circle"></i> Total: <span id="total-ingredientes">{{ count($ingredientes) }}</span>
                    </span>
                </div>
            </div>
            <ol class="breadcrumb" style="background: #f9f9f9; border-radius: 4px; padding: 10px 15px;">
                <li><a href="{{ url('Inicio') }}" style="color: #444;"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active" style="color: #3c8dbc;">Ingredientes</li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
                <div class="box-header with-border" style="background: linear-gradient(to right, #f9f9f9, #e0e0e0);">
                    <h3 class="box-title" style="font-weight: 600; color: #444;">
                        <i class="fa fa-list"></i> Listado de Ingredientes
                    </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarIngrediente">
                            <i class="fa fa-plus-circle"></i> Nuevo Ingrediente
                        </button>
                        
                        <div class="btn-group" style="margin-left: 10px;">
                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-file-pdf"></i> Reportes <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ route('ingredientes.pdf') }}" target="_blank"><i class="fa fa-list text-primary"></i> Reporte Completo</a></li>
                                <li><a href="{{ route('ingredientes.pdfminimo') }}" target="_blank"><i class="fa fa-arrow-down text-danger"></i> Stock Mínimo</a></li>
                                <li><a href="{{ route('ingredientes.pdfmaximo') }}" target="_blank"><i class="fa fa-arrow-up text-warning"></i> Stock Máximo</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="box-body" style="padding-bottom: 0;">
                    <div class="alert-container" style="margin-bottom: 15px;">
                        <div class="alert alert-danger alert-dismissible low-stock-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="fa fa-exclamation-triangle"></i> Alerta Crítica:</strong> 
                            Tienes <span id="critical-stock-count">0</span> ingredientes con stock bajo el mínimo
                            <a href="#" id="view-critical" class="alert-link">Ver detalles</a>
                        </div>
                        
                        <div class="alert alert-warning alert-dismissible near-minimum-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="fa fa-exclamation-circle"></i> Precaución:</strong> 
                            Tienes <span id="near-minimum-count">0</span> ingredientes cerca del mínimo
                            <a href="#" id="view-near-minimum" class="alert-link">Ver detalles</a>
                        </div>
                        
                        <div class="alert alert-info alert-dismissible excess-stock-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="fa fa-info-circle"></i> Nota:</strong> 
                            Tienes <span id="excess-stock-count">0</span> ingredientes con exceso de stock
                            <a href="#" id="view-excess" class="alert-link">Ver detalles</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="search-input" class="form-control" placeholder="Buscar ingrediente...">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="filter-status" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="bajo">Bajo stock</option>
                                    <option value="normal">Stock normal</option>
                                    <option value="exceso">Exceso de stock</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="filter-medida" class="form-control">
                                    <option value="">Todas las medidas</option>
                                    @foreach($medidas as $medida)
                                        <option value="{{ $medida->id }}">{{ $medida->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <button id="reset-filters" class="btn btn-default btn-block">
                                <i class="fa fa-undo"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="box-body table-responsive">
                    <table class="table table-hover table-striped" id="ingredientes-table">
                        <thead>
                            <tr class="bg-primary" style="color: white;">
                                <th><i class="fa fa-tag"></i> Nombre</th>
                                <th><i class="fa fa-arrow-up"></i> Stock Máx</th>
                                <th><i class="fa fa-arrow-down"></i> Stock Mín</th>
                                <th><i class="fa fa-boxes"></i> Stock Actual</th>
                                <th><i class="fa fa-ruler"></i> Medida</th>
                                <th><i class="fa fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ingredientes as $ingrediente)
                            <tr class="@if($ingrediente->Stock < $ingrediente->Stock_minimo) danger-row
                                      @elseif($ingrediente->Stock > $ingrediente->Stock_maximo) warning-row
                                      @else success-row @endif"
                                data-status="@if($ingrediente->Stock < $ingrediente->Stock_minimo)bajo
                                      @elseif($ingrediente->Stock > $ingrediente->Stock_maximo)exceso
                                      @else normal @endif"
                                data-medida="{{ $ingrediente->id_medidas }}"
                                data-nombre="{{ $ingrediente->Nombre }}"
                                data-stock="{{ $ingrediente->Stock }}"
                                data-minimo="{{ $ingrediente->Stock_minimo }}"
                                data-maximo="{{ $ingrediente->Stock_maximo }}">
                                <td>{{ $ingrediente->Nombre }}</td>
                                <td>{{ $ingrediente->Stock_maximo }}</td>
                                <td>{{ $ingrediente->Stock_minimo }}</td>
                                <td>
                                    <div class="editable-stock" data-id="{{ $ingrediente->id }}" data-original-value="{{ $ingrediente->Stock }}">
                                        <div class="progress" style="height: 25px; margin-bottom: 0;">
                                            @php
                                                $porcentaje = ($ingrediente->Stock / $ingrediente->Stock_maximo) * 100;
                                                $clase = ($ingrediente->Stock < $ingrediente->Stock_minimo) ? 'progress-bar-danger' : 
                                                        (($ingrediente->Stock > $ingrediente->Stock_maximo) ? 'progress-bar-warning' : 'progress-bar-success');
                                            @endphp
                                            <div class="progress-bar {{ $clase }} progress-bar-striped" 
                                                 role="progressbar" 
                                                 style="width: {{ $porcentaje }}%; line-height: 25px;"
                                                 aria-valuenow="{{ $ingrediente->Stock }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $ingrediente->Stock_maximo }}">
                                                {{ $ingrediente->Stock }} ({{ number_format($porcentaje, 1) }}%)
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $ingrediente->medida->nombre }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning btn-sm btnEditar" 
                                                data-id="{{ $ingrediente->id }}"
                                                data-nombre="{{ $ingrediente->Nombre }}"
                                                data-stock_maximo="{{ $ingrediente->Stock_maximo }}"
                                                data-stock_minimo="{{ $ingrediente->Stock_minimo }}"
                                                data-stock="{{ $ingrediente->Stock }}"
                                                data-id_medidas="{{ $ingrediente->id_medidas }}"
                                                data-toggle="modal"
                                                data-target="#modalEditarIngrediente">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm btnEliminarIngrediente" idIngrediente="{{ $ingrediente->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="no-results" class="text-center" style="display: none;">
                        <i class="fa fa-search" style="font-size: 48px; color: #ddd;"></i>
                        <h3>No se encontraron resultados</h3>
                        <p>Intenta con otros términos de búsqueda o filtros</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Agregar Ingrediente -->
    <div class="modal fade" id="modalAgregarIngrediente" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 8px;">
                <div class="modal-header" style="background: linear-gradient(to right, #3c8dbc, #367fa9); color: white;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Nuevo Ingrediente</h4>
                </div>
                <form id="formAgregarIngrediente" method="POST" action="{{ route('ingredientes.store') }}">
                    @csrf
                    <div class="modal-body" style="background: #f9f9f9;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre del Ingrediente</label>
                                    <input type="text" name="Nombre" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Máximo</label>
                                    <input type="number" name="Stock_maximo" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Mínimo</label>
                                    <input type="number" name="Stock_minimo" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Actual</label>
                                    <input type="number" name="Stock" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Medida</label>
                                    <select name="id_medidas" class="form-control input-lg" required>
                                        <option value="">Seleccionar medida</option>
                                        @foreach($medidas as $medida)
                                            <option value="{{ $medida->id }}">{{ $medida->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background: #f5f5f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Ingrediente -->
    <div class="modal fade" id="modalEditarIngrediente" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 8px;">
                <div class="modal-header" style="background: linear-gradient(to right, #3c8dbc, #367fa9); color: white;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Ingrediente</h4>
                </div>
                <form id="formEditarIngrediente" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="background: #f9f9f9;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre del Ingrediente</label>
                                    <input type="text" name="Nombre" id="edit_nombre" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Máximo</label>
                                    <input type="number" name="Stock_maximo" id="edit_stock_maximo" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Mínimo</label>
                                    <input type="number" name="Stock_minimo" id="edit_stock_minimo" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Actual</label>
                                    <input type="number" name="Stock" id="edit_stock" class="form-control input-lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Medida</label>
                                    <select name="id_medidas" id="edit_medida" class="form-control input-lg" required>
                                        @foreach($medidas as $medida)
                                            <option value="{{ $medida->id }}">{{ $medida->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background: #f5f5f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .danger-row {
            background-color: #ffdddd !important;
            border-left: 4px solid #f44336;
        }
        .danger-row:hover {
            background-color: #ffcccc !important;
        }
        
        .warning-row {
            background-color: #fff3cd !important;
            border-left: 4px solid #ffc107;
        }
        .warning-row:hover {
            background-color: #ffeeba !important;
        }
        
        .success-row {
            background-color: #ddffdd !important;
            border-left: 4px solid #4CAF50;
        }
        .success-row:hover {
            background-color: #ccffcc !important;
        }
        
        .progress {
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .progress-bar-danger {
            background-color: #f44336;
        }
        
        .progress-bar-warning {
            background-color: #ffc107;
        }
        
        .progress-bar-success {
            background-color: #4CAF50;
        }
        
        .table-hover tbody tr:hover {
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        
        .btn-group .btn {
            margin-right: 5px;
            border-radius: 4px !important;
        }
        
        #search-input, #filter-status, #filter-medida {
            border-radius: 4px;
            height: 38px;
        }
        
        #reset-filters {
            height: 38px;
        }
        
        #no-results {
            padding: 40px;
            background: #f9f9f9;
            border-radius: 4px;
            margin-top: 20px;
        }
        
        .alert-container .alert {
            margin-bottom: 10px;
            border-radius: 4px;
        }
        
        .alert-link {
            font-weight: bold;
            float: right;
        }
        
        .toast-top-right {
            top: 70px;
            right: 12px;
        }
        
        /* Estilos para la edición de stock */
        .editable-stock {
            cursor: pointer;
            padding: 5px;
            border-radius: 3px;
            transition: background-color 0.2s;
        }

        .editable-stock:hover {
            background-color: #f0f0f0;
        }

        .stock-input {
            width: 80px !important;
            height: 30px !important;
            text-align: center;
        }

        .input-group-btn .btn {
            padding: 5px 10px;
        }
        
        .input-group {
            width: auto;
            display: inline-flex;
        }
    </style>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery y Toastr -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
    $(document).ready(function() {
        // Configuración CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function checkStockLevels() {
            let criticalCount = 0;
            let nearMinimumCount = 0;
            let excessCount = 0;
            
            $('#ingredientes-table tbody tr').each(function() {
                const stock = parseFloat($(this).data('stock'));
                const minimo = parseFloat($(this).data('minimo'));
                const status = $(this).data('status');
                
                if (status === 'bajo') {
                    criticalCount++;
                } else if (stock < minimo * 1.2) {
                    nearMinimumCount++;
                } else if (status === 'exceso') {
                    excessCount++;
                }
            });
            
            if (criticalCount > 0) {
                $('#critical-stock-count').text(criticalCount);
                $('.low-stock-alert').show();
            } else {
                $('.low-stock-alert').hide();
            }
            
            if (nearMinimumCount > 0) {
                $('#near-minimum-count').text(nearMinimumCount);
                $('.near-minimum-alert').show();
            } else {
                $('.near-minimum-alert').hide();
            }
            
            if (excessCount > 0) {
                $('#excess-stock-count').text(excessCount);
                $('.excess-stock-alert').show();
            } else {
                $('.excess-stock-alert').hide();
            }
        }
        
        function filterTable() {
            const searchText = $('#search-input').val().toLowerCase();
            const statusFilter = $('#filter-status').val();
            const medidaFilter = $('#filter-medida').val();
            
            let hasResults = false;
            
            $('#ingredientes-table tbody tr').each(function() {
                const nombre = $(this).data('nombre').toLowerCase();
                const status = $(this).data('status');
                const medida = $(this).data('medida');
                
                const matchesSearch = nombre.includes(searchText);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesMedida = !medidaFilter || medida == medidaFilter;
                
                if (matchesSearch && matchesStatus && matchesMedida) {
                    $(this).show();
                    hasResults = true;
                } else {
                    $(this).hide();
                }
            });
            
            if (hasResults) {
                $('#no-results').hide();
            } else {
                $('#no-results').show();
            }
            
            const visibleCount = $('#ingredientes-table tbody tr:visible').length;
            $('#total-ingredientes').text(visibleCount);
            
            checkStockLevels();
        }
        
        // Eventos de filtrado
        $('#search-input').on('keyup', filterTable);
        $('#filter-status').on('change', filterTable);
        $('#filter-medida').on('change', filterTable);
        
        $('#reset-filters').on('click', function() {
            $('#search-input').val('');
            $('#filter-status').val('');
            $('#filter-medida').val('');
            filterTable();
        });
        
        // Visualización de alertas
        $('#view-critical').on('click', function(e) {
            e.preventDefault();
            $('#filter-status').val('bajo').trigger('change');
        });
        
        $('#view-near-minimum').on('click', function(e) {
            e.preventDefault();
            $('#search-input').val('');
            $('#filter-status').val('');
            $('#filter-medida').val('');
            
            $('#ingredientes-table tbody tr').each(function() {
                const stock = parseFloat($(this).data('stock'));
                const minimo = parseFloat($(this).data('minimo'));
                const isNearMinimum = stock < minimo * 1.2 && stock >= minimo;
                
                if (isNearMinimum) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        $('#view-excess').on('click', function(e) {
            e.preventDefault();
            $('#filter-status').val('exceso').trigger('change');
        });
        
        // Editar ingrediente (modal)
        $(document).on('click', '.btnEditar', function() {
            const ingrediente = {
                id: $(this).data('id'),
                nombre: $(this).data('nombre'),
                stock_maximo: $(this).data('stock_maximo'),
                stock_minimo: $(this).data('stock_minimo'),
                stock: $(this).data('stock'),
                id_medidas: $(this).data('id_medidas')
            };
            
            $('#edit_nombre').val(ingrediente.nombre);
            $('#edit_stock_maximo').val(ingrediente.stock_maximo);
            $('#edit_stock_minimo').val(ingrediente.stock_minimo);
            $('#edit_stock').val(ingrediente.stock);
            $('#edit_medida').val(ingrediente.id_medidas);
            
            $('#formEditarIngrediente').attr('action', '/ingredientes/' + ingrediente.id);
        });

        // Eliminar ingrediente
        $(document).on('click', '.btnEliminarIngrediente', function() {
            var idIng = $(this).attr('idIngrediente');

            Swal.fire({
                title: '¿Seguro que deseas eliminar este ingrediente?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/Eliminar-Ingrediente/' + idIng;
                }
            });
        });

        // Enviar formulario de edición
        $('#formEditarIngrediente').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const url = form.attr('action');
            
            $.ajax({
                url: url,
                type: 'PUT',
                data: form.serialize(),
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Edición directa del stock
        $(document).on('click', '.editable-stock', function() {
            const id = $(this).data('id');
            const currentValue = $(this).data('original-value');
            const container = $(this);
            
            // Guardar el HTML original para poder restaurarlo
            container.data('original-html', container.html());
            
            container.html(`
                <div class="input-group">
                    <input type="number" class="form-control input-sm stock-input" 
                           value="${currentValue}" min="0" step="0.01">
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm btn-guardar-stock">
                            <i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-default btn-sm btn-cancelar-stock">
                            <i class="fa fa-times"></i>
                        </button>
                    </span>
                </div>
            `);
            
            container.find('.stock-input').focus().select();
        });

        // Guardar el stock (edición directa)
        $(document).on('click', '.btn-guardar-stock', function(e) {
            e.stopPropagation();
            const container = $(this).closest('.editable-stock');
            const id = container.data('id');
            const newValue = container.find('.stock-input').val();
            
            $.ajax({
                url: '/ingredientes/' + id + '/update-stock',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    Stock: newValue
                },
                success: function(response) {
                    if(response.success) {
                        // Actualizar los datos en la fila
                        const row = container.closest('tr');
                        row.data('stock', newValue);
                        
                        // Recalcular el porcentaje
                        const stockMaximo = row.data('maximo');
                        const porcentaje = (newValue / stockMaximo) * 100;
                        
                        // Determinar la clase de la barra de progreso
                        let clase = 'progress-bar-success';
                        if(newValue < row.data('minimo')) {
                            clase = 'progress-bar-danger';
                        } else if(newValue > stockMaximo) {
                            clase = 'progress-bar-warning';
                        }
                        
                        // Reconstruir la barra de progreso
                        container.html(`
                            <div class="progress" style="height: 25px; margin-bottom: 0;">
                                <div class="progress-bar ${clase} progress-bar-striped" 
                                     role="progressbar" 
                                     style="width: ${porcentaje}%; line-height: 25px;"
                                     aria-valuenow="${newValue}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="${stockMaximo}">
                                    ${newValue} (${porcentaje.toFixed(1)}%)
                                </div>
                            </div>
                        `);
                        
                        // Actualizar el valor original
                        container.data('original-value', newValue);
                        
                        toastr.success(response.message);
                        
                        // Revisar los niveles de stock
                        checkStockLevels();
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message || 'Error al actualizar el stock');
                    console.error(xhr.responseText);
                }
            });
        });

        // Cancelar edición directa
        $(document).on('click', '.btn-cancelar-stock', function(e) {
            e.stopPropagation();
            const container = $(this).closest('.editable-stock');
            container.html(container.data('original-html'));
        });

        // Mostrar alerta inicial si hay stock bajo
        @if($ingredientes->where('Stock', '<', DB::raw('Stock_minimo'))->count() > 0)
        toastr.warning(
            'Tienes {{ $ingredientes->where("Stock", "<", DB::raw("Stock_minimo"))->count() }} ingredientes con stock bajo el mínimo', 
            'Alerta de Stock', 
            {
                timeOut: 10000,
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right"
            }
        );
        @endif
        
        // Verificar niveles de stock al cargar la página
        checkStockLevels();
    });
    </script>
@endsection