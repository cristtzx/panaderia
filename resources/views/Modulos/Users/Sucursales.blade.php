@extends('welcome')

@section('contenido')
<div class="content-wrapper">
    <!-- Header Section -->
    <section class="content-header">
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-8">
                <h1 class="text-primary" style="font-weight: 600;">
                    <i class="fa fa-store" style="color: #3c8dbc; margin-right: 10px;"></i> Gestión de Sucursales
                </h1>
            </div>
            <div class="col-md-4 text-right">
                <div style="display: inline-block; margin-right: 15px;">
                    <span class="badge bg-blue" style="font-size: 12px; padding: 8px 12px;">
                        <i class="fa fa-info-circle"></i> Activas: <span id="total-sucursales">{{ $sucursales->where('estado', 1)->count() }}</span>
                    </span>
                </div>
                <div style="display: inline-block;">
                    <span class="badge bg-gray" style="font-size: 12px; padding: 8px 12px;">
                        <i class="fa fa-info-circle"></i> Inactivas: <span id="total-inactivas">{{ $sucursales->where('estado', 0)->count() }}</span>
                    </span>
                </div>
            </div>
    </section>

    <!-- Main Content Section -->
    <section class="content">
        <!-- Active Branches -->
        <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
            <div class="box-header with-border" style="background: linear-gradient(to right, #f9f9f9, #e0e0e0);">
                <h3 class="box-title" style="font-weight: 600; color: #444;">
                    <i class="fa fa-store"></i> Sucursales Activas
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarSucursal">
                        <i class="fa fa-plus-circle"></i> Nueva Sucursal
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr class="bg-primary" style="color: white;">
                            <th><i class="fa fa-building"></i> Nombre</th>
                            <th><i class="fa fa-map-marker-alt"></i> Dirección</th>
                            <th><i class="fa fa-phone"></i> Teléfono</th>
                            <th><i class="fa fa-user-tie"></i> Encargado</th>
                            <th><i class="fa fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sucursales->where('estado', 1) as $sucursal)
                        <tr>
                            <td>
                                <a href="#" class="btn-ver-sucursal" 
                                   data-id="{{ $sucursal->id }}"
                                   data-nombre="{{ $sucursal->nombre }}"
                                   data-direccion="{{ $sucursal->direccion }}"
                                   data-telefono="{{ $sucursal->telefono }}"
                                   data-encargado="{{ $sucursal->encargado }}"
                                   data-latitud="{{ $sucursal->latitud }}"
                                   data-longitud="{{ $sucursal->longitud }}"
                                   data-estado="{{ $sucursal->estado }}">
                                    {{ $sucursal->nombre }}
                                </a>
                            </td>
                            <td>{{ $sucursal->direccion }}</td>
                            <td>{{ $sucursal->telefono }}</td>
                            <td>{{ $sucursal->encargado }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning btn-sm btnEditarSucursal"
                                            data-id="{{ $sucursal->id }}"
                                            data-nombre="{{ $sucursal->nombre }}"
                                            data-direccion="{{ $sucursal->direccion }}"
                                            data-telefono="{{ $sucursal->telefono }}"
                                            data-encargado="{{ $sucursal->encargado }}"
                                            data-toggle="modal"
                                            data-target="#modalEditarSucursal">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btnDesactivarSucursal"
                                        data-id="{{ $sucursal->id }}"
                                        data-nombre="{{ $sucursal->nombre }}">
                                        <i class="fa fa-power-off"></i> Desactivar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <i class="fa fa-store-slash" style="font-size: 40px; color: #ddd;"></i><br>
                                No hay sucursales activas registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inactive Branches (Collapsed by default) -->
        <div class="box box-default collapsed-box" style="border-top: 3px solid #d2d6de;">
            <div class="box-header with-border" style="background: linear-gradient(to right, #f9f9f9, #e0e0e0); cursor: pointer;" data-widget="collapse">
                <h3 class="box-title" style="font-weight: 600; color: #444;">
                    <i class="fa fa-store-slash"></i> Sucursales Inactivas
                    <span class="badge bg-gray" style="margin-left: 10px;">{{ $sucursales->where('estado', 0)->count() }}</span>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive" style="display: none;">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr class="bg-gray" style="color: #444;">
                            <th><i class="fa fa-building"></i> Nombre</th>
                            <th><i class="fa fa-map-marker-alt"></i> Dirección</th>
                            <th><i class="fa fa-phone"></i> Teléfono</th>
                            <th><i class="fa fa-user-tie"></i> Encargado</th>
                            <th><i class="fa fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sucursales->where('estado', 0) as $sucursal)
                        <tr>
                            <td>
                                <a href="#" class="btn-ver-sucursal" 
                                   data-id="{{ $sucursal->id }}"
                                   data-nombre="{{ $sucursal->nombre }}"
                                   data-direccion="{{ $sucursal->direccion }}"
                                   data-telefono="{{ $sucursal->telefono }}"
                                   data-encargado="{{ $sucursal->encargado }}"
                                   data-latitud="{{ $sucursal->latitud }}"
                                   data-longitud="{{ $sucursal->longitud }}"
                                   data-estado="{{ $sucursal->estado }}">
                                    {{ $sucursal->nombre }}
                                </a>
                            </td>
                            <td>{{ $sucursal->direccion }}</td>
                            <td>{{ $sucursal->telefono }}</td>
                            <td>{{ $sucursal->encargado }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-success btn-sm btnActivarSucursal"
                                        data-id="{{ $sucursal->id }}"
                                        data-nombre="{{ $sucursal->nombre }}">
                                        <i class="fa fa-power-off"></i> Habilitar
                                    </button>
                                    <button class="btn btn-danger btn-sm btnEliminarSucursal"
                                        data-id="{{ $sucursal->id }}"
                                        data-nombre="{{ $sucursal->nombre }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <i class="fa fa-check-circle" style="font-size: 40px; color: #ddd;"></i><br>
                                No hay sucursales inactivas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- ========== MODALS SECTION ========== -->

<!-- Add Branch Modal -->
<div class="modal fade" id="modalAgregarSucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #3c8dbc, #367fa9); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-store-alt"></i> Registrar Nueva Sucursal</h4>
            </div>

            <form method="POST" action="{{ route('sucursales.store') }}">
                @csrf
                <div class="modal-body" style="background: #f9f9f9;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de la Sucursal</label>
                                <input type="text" name="nombre" class="form-control input-lg" required>
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <div id="mapbox-buscador" style="margin-bottom: 10px;"></div>
                                <input type="hidden" name="direccion" id="direccion">
                                <input type="hidden" name="latitud" id="latitud">
                                <input type="hidden" name="longitud" id="longitud">
                                <div id="map" style="height: 200px; margin-top: 10px; border: 1px solid #ccc;"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="tel" name="telefono" class="form-control input-lg" required>
                            </div>
                            <div class="form-group">
                                <label>Encargado</label>
                                <input type="text" name="encargado" class="form-control input-lg" required>
                            </div>
                            <input type="hidden" name="estado" value="1"> <!-- Estado activo por defecto -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f5f5f5;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Guardar Sucursal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Branch Details Modal -->
<div class="modal fade" id="modalVerSucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #3c8dbc, #367fa9); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Detalles de Sucursal</h4>
            </div>
            <div class="modal-body" style="background: #f9f9f9;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background: #f5f5f5;">
                                <h3 class="panel-title">Información General</h3>
                            </div>
                            <div class="panel-body">
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-store"></i> Nombre:</span>
                                    <span class="info-value" id="view-nombre"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-map-marker-alt"></i> Dirección:</span>
                                    <span class="info-value" id="view-direccion"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-phone"></i> Teléfono:</span>
                                    <span class="info-value" id="view-telefono"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-user-tie"></i> Encargado:</span>
                                    <span class="info-value" id="view-encargado"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-power-off"></i> Estado:</span>
                                    <span class="info-value" id="view-estado"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background: #f5f5f5;">
                                <h3 class="panel-title">Ubicación</h3>
                            </div>
                            <div class="panel-body">
                                <div id="map-view" style="height: 300px; border: 1px solid #ddd;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f5f5f5;">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Branch Modal -->
<div class="modal fade" id="modalEditarSucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #3c8dbc, #367fa9); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Sucursal</h4>
            </div>

            <form id="formEditarSucursal" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="background: #f9f9f9;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de la Sucursal</label>
                                <input type="text" name="nombre" id="edit-nombre" class="form-control input-lg" required>
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" name="direccion" id="edit-direccion" class="form-control input-lg" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="tel" name="telefono" id="edit-telefono" class="form-control input-lg" required>
                            </div>
                            <div class="form-group">
                                <label>Encargado</label>
                                <input type="text" name="encargado" id="edit-encargado" class="form-control input-lg" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f5f5f5;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Actualizar Sucursal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Branch Modal -->
<div class="modal fade" id="modalEliminarSucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #d9534f, #c9302c); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-trash"></i> Confirmar Eliminación</h4>
            </div>
            <form id="formEliminarSucursal" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar la sucursal <strong id="textoSucursalEliminar"></strong>?</p>
                    <p class="text-danger"><i class="fa fa-exclamation-triangle"></i> Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer" style="background: #f5f5f5;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deactivate Branch Modal -->
<div class="modal fade" id="modalDesactivarSucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #f39c12, #e67e22); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-power-off"></i> Confirmar Desactivación</h4>
            </div>
            <form id="formDesactivarSucursal" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="estado" value="0">
                <div class="modal-body">
                    <p>¿Estás seguro que deseas desactivar la sucursal <strong id="textoSucursalDesactivar"></strong>?</p>
                    <p class="text-warning"><i class="fa fa-exclamation-triangle"></i> La sucursal no estará disponible para operaciones pero podrás reactivarla luego.</p>
                </div>
                <div class="modal-footer" style="background: #f5f5f5;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-power-off"></i> Desactivar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Activate Branch Modal -->
<div class="modal fade" id="modalActivarSucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #00a65a, #008d4c); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-power-off"></i> Confirmar Activación</h4>
            </div>
            <form id="formActivarSucursal" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="estado" value="1">
                <div class="modal-body">
                    <p>¿Estás seguro que deseas habilitar nuevamente la sucursal <strong id="textoSucursalActivar"></strong>?</p>
                    <p class="text-success"><i class="fa fa-check-circle"></i> La sucursal estará disponible para operaciones.</p>
                </div>
                <div class="modal-footer" style="background: #f5f5f5;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-power-off"></i> Habilitar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== SCRIPTS SECTION ========== -->

<!-- Mapbox Resources -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css' rel='stylesheet' />

<!-- Toastr Notifications -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
    .info-item {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .info-label {
        font-weight: bold;
        color: #555;
        display: block;
        margin-bottom: 5px;
    }
    .info-value {
        color: #333;
        font-size: 16px;
    }
    .btn-ver-sucursal {
        color: #3c8dbc;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none !important;
    }
    .btn-ver-sucursal:hover {
        color: #2a6496;
        text-decoration: underline !important;
    }
    /* Estilo para filas de sucursales inactivas */
    .table tbody tr td {
        color: #999;
    }
    /* Estilo para el panel de sucursales inactivas */
    .box-default .box-header {
        border-bottom: 1px solid #d2d6de;
    }
    /* Estilo para los badges */
    .badge.bg-blue {
        background-color: #3c8dbc;
    }
    .badge.bg-gray {
        background-color: #d2d6de;
        color: #444;
    }
</style>

<script>
    // Mapbox Configuration
    mapboxgl.accessToken = 'pk.eyJ1IjoicmV5ZXMxMiIsImEiOiJjbWNjcHk1Y3YwYTczMmpvbG9pdm82Nno1In0.haY2uVOSBL2g4fSCYp6Edw';

    let map, marker, geocoder;
    let viewMap, viewMarker;

    function initMapbox() {
        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [-99.1332, 19.4326],
            zoom: 13
        });

        geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl,
            marker: false,
            placeholder: 'Buscar dirección...'
        });

        document.getElementById('mapbox-buscador').appendChild(geocoder.onAdd(map));

        marker = new mapboxgl.Marker({ draggable: true })
            .setLngLat([-99.1332, 19.4326])
            .addTo(map);

        geocoder.on('result', function(e) {
            const coords = e.result.geometry.coordinates;
            const place = e.result.place_name;

            map.flyTo({ center: coords, zoom: 16 });
            marker.setLngLat(coords);

            document.getElementById('direccion').value = place;
            document.getElementById('latitud').value = coords[1];
            document.getElementById('longitud').value = coords[0];
        });

        marker.on('dragend', function() {
            const lngLat = marker.getLngLat();
            document.getElementById('latitud').value = lngLat.lat;
            document.getElementById('longitud').value = lngLat.lng;
        });
    }

    function initViewMap(lng, lat) {
        if (viewMap) {
            viewMap.remove();
        }
        
        viewMap = new mapboxgl.Map({
            container: 'map-view',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [lng, lat],
            zoom: 15
        });

        if (viewMarker) {
            viewMarker.remove();
        }

        viewMarker = new mapboxgl.Marker({ color: '#3c8dbc' })
            .setLngLat([lng, lat])
            .addTo(viewMap);
            
        // Add zoom controls
        viewMap.addControl(new mapboxgl.NavigationControl());
    }

    // Initialize map when add modal is shown
    $('#modalAgregarSucursal').on('shown.bs.modal', function () {
        if (!map) {
            initMapbox();
        }
    });

    // View Branch Details Handler
    $(document).on('click', '.btn-ver-sucursal', function(e) {
        e.preventDefault();
        
        const nombre = $(this).data('nombre');
        const direccion = $(this).data('direccion');
        const telefono = $(this).data('telefono');
        const encargado = $(this).data('encargado');
        const estado = $(this).data('estado');
        const latitud = parseFloat($(this).data('latitud'));
        const longitud = parseFloat($(this).data('longitud'));
        
        $('#view-nombre').text(nombre);
        $('#view-direccion').text(direccion);
        $('#view-telefono').text(telefono);
        $('#view-encargado').text(encargado);
        $('#view-estado').html(estado == 1 
            ? '<span class="label label-success">Activa</span>' 
            : '<span class="label label-danger">Inactiva</span>');
        
        $('#modalVerSucursal').modal('show');
        
        // Initialize or update the view map after modal is shown
        $('#modalVerSucursal').on('shown.bs.modal', function() {
            initViewMap(longitud, latitud);
        });
    });

    // Edit Branch Handler
    $(document).on('click', '.btnEditarSucursal', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let direccion = $(this).data('direccion');
        let telefono = $(this).data('telefono');
        let encargado = $(this).data('encargado');

        $('#edit-nombre').val(nombre);
        $('#edit-direccion').val(direccion);
        $('#edit-telefono').val(telefono);
        $('#edit-encargado').val(encargado);

        $('#formEditarSucursal').attr('action', '/sucursales/' + id);
    });

    // Delete Branch Handler
    $(document).on('click', '.btnEliminarSucursal', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');

        $('#textoSucursalEliminar').text(nombre);
        $('#formEliminarSucursal').attr('action', '/sucursales/' + id);
        $('#modalEliminarSucursal').modal('show');
    });

    // Deactivate Branch Handler
    $(document).on('click', '.btnDesactivarSucursal', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');

        $('#textoSucursalDesactivar').text(nombre);
        $('#formDesactivarSucursal').attr('action', '/sucursales/' + id + '/estado');
        $('#modalDesactivarSucursal').modal('show');
    });

    // Activate Branch Handler
    $(document).on('click', '.btnActivarSucursal', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');

        $('#textoSucursalActivar').text(nombre);
        $('#formActivarSucursal').attr('action', '/sucursales/' + id + '/estado');
        $('#modalActivarSucursal').modal('show');
    });

    // Refresh counters after modal actions
    $(document).on('submit', '#formDesactivarSucursal, #formActivarSucursal, #formEliminarSucursal', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const method = form.find('input[name="_method"]').val() || 'POST';
        
        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),
            success: function(response) {
                // Cierra el modal
                form.closest('.modal').modal('hide');
                
                // Muestra mensaje de éxito
                toastr.success(response.message || 'Operación realizada con éxito');
                
                // Recarga la página después de 1.5 segundos
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message || 'Error al realizar la operación');
            }
        });
    });

    // Configuración de Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
</script>
@endsection