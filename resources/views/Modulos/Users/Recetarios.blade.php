@extends('welcome2')

@section('contenido')
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-book text-primary"></i> Gestión de Recetas</h1>
    </section>

    <section class="content">
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalReceta">
            <i class="fa fa-plus"></i> Nueva Receta
        </button>
<a href="{{ route('recetas.todas.pdf') }}" 
   target="_blank" 
   class="btn btn-warning">
    <i class="fas fa-file-pdf me-2"></i>
    Generar PDF Completo
</a>

        <!-- Botón para buscar postres externos -->
        <button class="btn btn-info mb-3" id="btnBuscarPostres">
            <i class="fa fa-ice-cream"></i> Buscar Postres Externos
        </button>

        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>
        @endif

        @if($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: `@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach`,
                    confirmButtonText: 'Entendido'
                });
            </script>
        @endif

        <!-- Tabla de recetas existentes -->
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Instrucciones</th>
                            <th>Tiempo (min)</th>
                            <th>Ingredientes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recetas as $receta)
                        <tr>
                            <td>
                                <a href="#" class="btn-ver-receta" data-id="{{ $receta->id }}" style="color: #337ab7; text-decoration: none; cursor: pointer;">
                                    {{ $receta->nombre }}
                                </a>
                            </td>
                            <td>{{ Str::limit($receta->descripcion, 30) }}</td>
                            <td>{{ Str::limit($receta->instrucciones, 30) }}</td>
                            <td>{{ $receta->tiempo_preparacion }}</td>
                            <td>
                                @foreach($receta->ingredientes as $ing)
                                    {{ $ing->Nombre }} ({{ $ing->pivot->cantidad }} {{ $ing->pivot->unidad }})<br>
                                @endforeach
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info btn-editar-receta" data-id="{{ $receta->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btnEliminarReceta" idReceta="{{ $receta->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <td>

        <a href="{{ route('recetas.pdf', $receta->id) }}" class="btn btn-sm btn-warning">
            <i class="fa fa-file-pdf"></i> PDF
        </a>
    </div>
</td>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal para nueva receta -->
<div class="modal fade" id="modalReceta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Nueva Receta</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formReceta" action="{{ route('recetas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de la Receta *</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tiempo de preparación (minutos) *</label>
                                <input type="number" name="tiempo_preparacion" class="form-control" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="descripcion" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Instrucciones de preparación *</label>
                        <textarea name="instrucciones" class="form-control" rows="3" required></textarea>
                    </div>

                    <hr>
                    <h5>Ingredientes</h5>
                    <div id="ingredientes-container">
                        <div class="row mb-2 ingrediente-row">
                            <div class="col-md-5">
                                <select name="ingredientes[0][id]" class="form-control" required>
                                    <option value="">Seleccione ingrediente</option>
                                    @foreach($ingredientes as $ing)
                                        <option value="{{ $ing->id }}">{{ $ing->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="ingredientes[0][cantidad]" class="form-control" min="0.1" step="0.01" placeholder="Cantidad" required>
                            </div>
                            <div class="col-md-3">
                                <select name="ingredientes[0][unidad]" class="form-control" required>
                                    <option value="g">Gramos (g)</option>
                                    <option value="kg">Kilogramos (kg)</option>
                                    <option value="ml">Mililitros (ml)</option>
                                    <option value="l">Litros (l)</option>
                                    <option value="taza">Tazas</option>
                                    <option value="cda">Cucharadas</option>
                                    <option value="cdita">Cucharaditas</option>
                                    <option value="pizca">Pizca</option>
                                    <option value="unidades">Unidades</option>
                                    <option value="hojas">Hojas</option>
                                    <option value="dientes">Dientes (ajo)</option>
                                    <option value="rebanadas">Rebanadas</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-remove" disabled>
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="btn-add" class="btn btn-sm btn-success mt-2">
                        <i class="fa fa-plus"></i> Agregar Ingrediente
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Receta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar receta -->
<div class="modal fade" id="modalEditarReceta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Editar Receta</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditarReceta" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de la Receta *</label>
                                <input type="text" name="nombre" id="editNombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tiempo de preparación (minutos) *</label>
                                <input type="number" name="tiempo_preparacion" id="editTiempo" class="form-control" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="descripcion" id="editDescripcion" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Instrucciones de preparación *</label>
                        <textarea name="instrucciones" id="editInstrucciones" class="form-control" rows="3" required></textarea>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Ingredientes</h5>
                        <button type="button" id="btn-add-edit" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i> Agregar Ingrediente
                        </button>
                    </div>
                    
                    <div id="ingredientes-container-edit">
                        <!-- Los ingredientes se cargarán dinámicamente aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de receta -->
<div class="modal fade" id="modalVerReceta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Detalles de la Receta</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Nombre:</strong></label>
                            <p id="viewNombre" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Tiempo de preparación:</strong></label>
                            <p id="viewTiempo" class="form-control-static"></p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Descripción:</strong></label>
                    <p id="viewDescripcion" class="form-control-static"></p>
                </div>

                <div class="form-group">
                    <label><strong>Instrucciones:</strong></label>
                    <div id="viewInstrucciones" class="form-control-static" style="white-space: pre-line; background: #f8f9fa; padding: 10px; border-radius: 4px;"></div>
                </div>

                <hr>
                <h5><strong>Ingredientes:</strong></h5>
                <div id="viewIngredientes" class="list-group">
                    <!-- Los ingredientes se cargarán dinámicamente aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para recetas externas (postres) -->
<div class="modal fade" id="modalPostresExternos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="modal-title"><i class="fa fa-ice-cream"></i> Postres Externos</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="resultadosPostres" class="row">
                    <div class="col-12 text-center py-4">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Cargando postres...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de receta externa -->
<div class="modal fade" id="modalDetalleExterno">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- El contenido se carga dinámicamente -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Configuración global de SweetAlert
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary mx-2',
            cancelButton: 'btn btn-danger mx-2'
        },
        buttonsStyling: false
    });

    // Manejo de ingredientes en creación
    let counter = 1;
    let editCounter = 0;
    
    // Agregar nuevo ingrediente (para creación)
    $('#btn-add').click(function() {
        const newRow = `
        <div class="row mb-2 ingrediente-row">
            <div class="col-md-5">
                <select name="ingredientes[${counter}][id]" class="form-control" required>
                    <option value="">Seleccione ingrediente</option>
                    @foreach($ingredientes as $ing)
                        <option value="{{ $ing->id }}">{{ $ing->Nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="ingredientes[${counter}][cantidad]" class="form-control" min="0.1" step="0.01" placeholder="Cantidad" required>
            </div>
            <div class="col-md-3">
                <select name="ingredientes[${counter}][unidad]" class="form-control" required>
                    <option value="g">Gramos (g)</option>
                    <option value="kg">Kilogramos (kg)</option>
                    <option value="ml">Mililitros (ml)</option>
                    <option value="l">Litros (l)</option>
                    <option value="taza">Tazas</option>
                    <option value="cda">Cucharadas</option>
                    <option value="cdita">Cucharaditas</option>
                    <option value="pizca">Pizca</option>
                    <option value="unidades">Unidades</option>
                    <option value="hojas">Hojas</option>
                    <option value="dientes">Dientes (ajo)</option>
                    <option value="rebanadas">Rebanadas</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-remove">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;
        
        $('#ingredientes-container').append(newRow);
        counter++;
        $('.btn-remove').prop('disabled', false);
    });
    
    // Eliminar ingrediente (para creación)
    $(document).on('click', '.btn-remove', function() {
        if($('.ingrediente-row').length > 1) {
            $(this).closest('.ingrediente-row').remove();
            if($('.ingrediente-row').length === 1) {
                $('.btn-remove').prop('disabled', true);
            }
        }
    });
    
    // Eliminar receta con SweetAlert2
    $(document).on('click', '.btnEliminarReceta', function() {
        var idReceta = $(this).attr('idReceta');

        swalWithBootstrapButtons.fire({
            title: '¿Seguro que deseas eliminar esta receta?',
            text: "¡Esta acción no se puede deshacer! (Los ingredientes no se eliminarán)",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Eliminando...',
                    html: 'Por favor espere',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Enviar solicitud de eliminación
                $.ajax({
                    url: '/Eliminar-Receta/' + idReceta,
                    type: 'GET',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'La receta ha sido eliminada',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar la receta',
                            confirmButtonText: 'Entendido'
                        });
                    }
                });
            }
        });
    });

    // Cargar modal de edición
    $(document).on('click', '.btn-editar-receta', function() {
        var recetaId = $(this).data('id');
        
        // Limpiar el contenedor de ingredientes
        $('#ingredientes-container-edit').empty();
        editCounter = 0;
        
        // Mostrar loading
        Swal.fire({
            title: 'Cargando...',
            html: 'Obteniendo datos de la receta',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Obtener los datos de la receta via AJAX
        $.get('/recetas/' + recetaId + '/edit', function(response) {
            Swal.close();
            
            if (response.success) {
                var data = response.data;
                
                // Llenar los campos del formulario
                $('#formEditarReceta').attr('action', '/recetas/' + recetaId);
                $('#editNombre').val(data.receta.nombre);
                $('#editDescripcion').val(data.receta.descripcion);
                $('#editInstrucciones').val(data.receta.instrucciones);
                $('#editTiempo').val(data.receta.tiempo_preparacion);
                
                // Agregar los ingredientes existentes
                if (data.ingredientes && data.ingredientes.length > 0) {
                    data.ingredientes.forEach(function(ingrediente) {
                        addEditIngredientRow(
                            ingrediente.id,
                            ingrediente.nombre,
                            ingrediente.cantidad,
                            ingrediente.unidad,
                            data.all_ingredientes
                        );
                    });
                } else {
                    // Agregar una fila vacía si no hay ingredientes
                    addEditIngredientRow('', '', '', 'g', data.all_ingredientes);
                }
                
                // Mostrar el modal
                $('#modalEditarReceta').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudieron cargar los datos de la receta',
                    confirmButtonText: 'Entendido'
                });
            }
        }).fail(function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los datos de la receta: ' + error,
                confirmButtonText: 'Entendido'
            });
        });
    });

    // Ver detalles de la receta al hacer clic en el nombre
    $(document).on('click', '.btn-ver-receta', function(e) {
        e.preventDefault();
        var recetaId = $(this).data('id');
        
        // Mostrar loading
        Swal.fire({
            title: 'Cargando...',
            html: 'Obteniendo detalles de la receta',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.get('/recetas/' + recetaId + '/edit', function(response) {
            Swal.close();
            
            if (response.success) {
                var data = response.data;
                
                // Llenar los campos del modal de visualización
                $('#viewNombre').text(data.receta.nombre);
                $('#viewDescripcion').text(data.receta.descripcion);
                $('#viewInstrucciones').text(data.receta.instrucciones);
                $('#viewTiempo').text(data.receta.tiempo_preparacion + ' minutos');
                
                // Limpiar y cargar los ingredientes
                $('#viewIngredientes').empty();
                if (data.ingredientes && data.ingredientes.length > 0) {
                    data.ingredientes.forEach(function(ingrediente) {
                        $('#viewIngredientes').append(
                            `<div class="list-group-item">
                                <strong>${ingrediente.nombre}</strong> - ${ingrediente.cantidad} ${ingrediente.unidad}
                            </div>`
                        );
                    });
                } else {
                    $('#viewIngredientes').append(
                        '<div class="list-group-item">No hay ingredientes registrados</div>'
                    );
                }
                
                // Mostrar el modal
                $('#modalVerReceta').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los datos de la receta',
                    confirmButtonText: 'Entendido'
                });
            }
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los datos de la receta',
                confirmButtonText: 'Entendido'
            });
        });
    });

    // Agregar nuevo ingrediente (para edición)
    $('#btn-add-edit').click(function() {
        // Obtener la lista actual de ingredientes del primer select
        var allIngredients = [];
        $('#ingredientes-container-edit select[name^="ingredientes["]').first().find('option').each(function() {
            if ($(this).val()) {
                allIngredients.push({
                    id: $(this).val(),
                    nombre: $(this).text()
                });
            }
        });
        
        addEditIngredientRow('', '', '', 'g', allIngredients);
    });
    
    // Eliminar ingrediente (para edición)
    $(document).on('click', '.btn-remove-edit', function() {
        if($('#ingredientes-container-edit .ingrediente-row').length > 1) {
            $(this).closest('.ingrediente-row').remove();
            if($('#ingredientes-container-edit .ingrediente-row').length === 1) {
                $('.btn-remove-edit').prop('disabled', true);
            }
            // Reindexar los nombres de los campos
            reindexEditIngredientRows();
        }
    });

    // Función para agregar filas de ingredientes en edición
    function addEditIngredientRow(id, nombre, cantidad, unidad, allIngredients) {
        // Construir opciones de ingredientes
        let options = '<option value="">Seleccione ingrediente</option>';
        if (allIngredients && allIngredients.length > 0) {
            allIngredients.forEach(function(ing) {
                options += `<option value="${ing.id}" ${id == ing.id ? 'selected' : ''}>${ing.nombre}</option>`;
            });
        }
        
        const newRow = `
        <div class="row mb-3 ingrediente-row">
            <div class="col-md-5">
                <select name="ingredientes[${editCounter}][id]" class="form-control" required>
                    ${options}
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="ingredientes[${editCounter}][cantidad]" 
                       class="form-control" min="0.1" step="0.01" required
                       value="${cantidad || ''}">
            </div>
            <div class="col-md-3">
                <select name="ingredientes[${editCounter}][unidad]" class="form-control" required>
                    <option value="g" ${unidad == 'g' ? 'selected' : ''}>Gramos (g)</option>
                    <option value="kg" ${unidad == 'kg' ? 'selected' : ''}>Kilogramos (kg)</option>
                    <option value="ml" ${unidad == 'ml' ? 'selected' : ''}>Mililitros (ml)</option>
                    <option value="l" ${unidad == 'l' ? 'selected' : ''}>Litros (l)</option>
                    <option value="taza" ${unidad == 'taza' ? 'selected' : ''}>Tazas</option>
                    <option value="cda" ${unidad == 'cda' ? 'selected' : ''}>Cucharadas</option>
                    <option value="cdita" ${unidad == 'cdita' ? 'selected' : ''}>Cucharaditas</option>
                    <option value="pizca" ${unidad == 'pizca' ? 'selected' : ''}>Pizca</option>
                    <option value="unidades" ${unidad == 'unidades' ? 'selected' : ''}>Unidades</option>
                    <option value="hojas" ${unidad == 'hojas' ? 'selected' : ''}>Hojas</option>
                    <option value="dientes" ${unidad == 'dientes' ? 'selected' : ''}>Dientes (ajo)</option>
                    <option value="rebanadas" ${unidad == 'rebanadas' ? 'selected' : ''}>Rebanadas</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-remove-edit" ${editCounter === 0 ? 'disabled' : ''}>
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;
        
        $('#ingredientes-container-edit').append(newRow);
        editCounter++;
    }

    // Función para reindexar los campos de ingredientes en edición
    function reindexEditIngredientRows() {
        editCounter = 0;
        $('#ingredientes-container-edit .ingrediente-row').each(function(index) {
            $(this).find('select[name^="ingredientes["]').attr('name', `ingredientes[${index}][id]`);
            $(this).find('input[name^="ingredientes["]').attr('name', `ingredientes[${index}][cantidad]`);
            $(this).find('select[name^="ingredientes["][name$="[unidad]"]').attr('name', `ingredientes[${index}][unidad]`);
            $(this).find('.btn-remove-edit').prop('disabled', index === 0);
            editCounter++;
        });
    }

    // Manejo de envío de formularios con validación
    $('#formReceta, #formEditarReceta').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        // Validar que al menos un ingrediente tenga cantidad > 0
        let hasValidIngredients = false;
        $(this).find('input[name*="[cantidad]"]').each(function() {
            if (parseFloat($(this).val()) > 0) {
                hasValidIngredients = true;
                return false; // Salir del each
            }
        });
        
        if (!hasValidIngredients) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe agregar al menos un ingrediente con cantidad mayor a cero',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        
        swalWithBootstrapButtons.fire({
            title: '¿Guardar receta?',
            text: '¿Estás seguro de que deseas guardar esta receta?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading mientras se procesa
                Swal.fire({
                    title: 'Procesando...',
                    html: 'Guardando la receta',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Enviar el formulario
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Receta guardada!',
                            text: 'La receta se ha guardado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON && xhr.responseJSON.errors;
                        let errorMessage = 'Error al guardar la receta';
                        
                        if (errors) {
                            errorMessage = Object.values(errors).join('<br>');
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                            confirmButtonText: 'Entendido'
                        });
                    }
                });
            }
        });
    });

    // ==============================================
    // CÓDIGO PARA MANEJAR RECETAS EXTERNAS (POSTRES)
    // ==============================================

    // Botón para buscar postres externos
    $('#btnBuscarPostres').click(function() {
        $('#modalPostresExternos').modal('show');
        cargarPostresExternos();
    });

    // Función para cargar postres de TheMealDB
    function cargarPostresExternos() {
        $('#resultadosPostres').html(`
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Buscando postres...</p>
            </div>
        `);

        // Llamada a la API de TheMealDB para postres (Dessert category)
        $.get('https://www.themealdb.com/api/json/v1/1/filter.php?c=Dessert', function(data) {
            if (data.meals && data.meals.length > 0) {
                let html = '';
                data.meals.forEach(meal => {
                    html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="${meal.strMealThumb}" class="card-img-top" style="height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">${meal.strMeal}</h5>
                                <button class="btn btn-sm btn-outline-primary btn-ver-detalle-externo" 
                                        data-id="${meal.idMeal}">
                                    <i class="fa fa-eye"></i> Ver Receta
                                </button>
                            </div>
                        </div>
                    </div>`;
                });
                $('#resultadosPostres').html(html);
            } else {
                $('#resultadosPostres').html(`
                    <div class="col-12 text-center py-4">
                        <i class="fa fa-exclamation-circle fa-2x text-muted"></i>
                        <p class="mt-2">No se encontraron postres.</p>
                    </div>
                `);
            }
        }).fail(() => {
            $('#resultadosPostres').html(`
                <div class="col-12 text-center py-4">
                    <i class="fa fa-times-circle fa-2x text-danger"></i>
                    <p class="mt-2">Error al conectar con TheMealDB.</p>
                </div>
            `);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con la API de recetas externas',
                confirmButtonText: 'Entendido'
            });
        });
    }

    // Ver detalles de receta externa (postre)
    $(document).on('click', '.btn-ver-detalle-externo', function() {
        const mealId = $(this).data('id');
        
        // Mostrar spinner en el modal de detalles
        $('#modalDetalleExterno .modal-content').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Cargando receta...</p>
            </div>
        `);
        
        $('#modalDetalleExterno').modal('show');

        // Obtener detalles de la receta
        $.get(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${mealId}`, function(data) {
            const meal = data.meals[0];
            let ingredientesHtml = '';
            
            // Procesar ingredientes (strIngredient1...20 + strMeasure1...20)
            for (let i = 1; i <= 20; i++) {
                if (meal[`strIngredient${i}`] && meal[`strIngredient${i}`].trim() !== '') {
                    ingredientesHtml += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${meal[`strIngredient${i}`]}
                            <span class="badge bg-primary rounded-pill">${meal[`strMeasure${i}`] || 'al gusto'}</span>
                        </li>`;
                }
            }

            // Construir el contenido del modal
            const modalContent = `
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fa fa-ice-cream"></i> ${meal.strMeal}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="${meal.strMealThumb}" class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">
                            ${meal.strTags ? `<p><strong>Etiquetas:</strong> ${meal.strTags.split(',').join(', ')}</p>` : ''}
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fa fa-list"></i> Ingredientes:</h5>
                            <ul class="list-group mb-3">${ingredientesHtml}</ul>
                        </div>
                    </div>
                    <hr>
                    <h5><i class="fa fa-book"></i> Instrucciones:</h5>
                    <div class="card">
                        <div class="card-body" style="white-space: pre-line">${meal.strInstructions}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    ${meal.strYoutube ? `
                    <a href="${meal.strYoutube}" target="_blank" class="btn btn-danger">
                        <i class="fab fa-youtube"></i> Ver en YouTube
                    </a>` : ''}
                </div>
            `;

            $('#modalDetalleExterno .modal-content').html(modalContent);
        }).fail(() => {
            $('#modalDetalleExterno .modal-content').html(`
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fa fa-times-circle fa-3x text-danger mb-3"></i>
                    <p>No se pudo cargar la receta. Por favor, intenta nuevamente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            `);
        });
    });
});
</script>
@endsection