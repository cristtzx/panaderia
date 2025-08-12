@extends('welcome')

<style>
    /* ESTILOS PRINCIPALES */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8fafc;
    }
    
    .content-wrapper {
        padding: 20px;
    }
    
    /* TABLA - AZUL PRINCIPAL (#3490dc) */
    .table {
        font-size: 14px;
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }
    
    .table th {
        background-color: #3490dc;
        color: white;
        font-weight: 600;
        border: none;
        padding: 12px 15px;
    }
    
    .table td {
        background-color: white;
        vertical-align: middle;
        padding: 12px 15px;
        border: none;
    }
    
    .table>tbody>tr {
        box-shadow: 0 2px 4px rgba(52, 144, 220, 0.1);
        transition: all 0.3s ease;
        border-radius: 4px;
    }
    
    .table>tbody>tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 144, 220, 0.15);
        background-color: #f0f7ff;
    }
    
    /* BOTONES */
    .btn-xs {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
        margin: 2px;
        transition: all 0.2s;
    }
    
    .btn-warning {
        background-color: #f39c12;
        border-color: #e08e0b;
    }
    
    .btn-danger {
        background-color: #e3342f;
        border-color: #d32722;
    }
    
    /* MODALES */
    .modal-header {
        background-color: #3490dc;
        color: white;
        border-bottom: none;
        padding: 15px 20px;
        border-radius: 5px 5px 0 0;
    }
    
    /* FILTROS */
    .filtros-container {
        background: white;
        padding: 20px;
        margin-bottom: 25px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(52, 144, 220, 0.1);
        border-top: 3px solid #3490dc;
    }
    
    .filtros-title {
        color: #3490dc;
        font-size: 16px;
        font-weight: 600;
        border-bottom: 1px solid #e6f0fa;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    /* ESTADOS */
    .btnEstadoUser {
        min-width: 90px;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }
    
    .btn-success {
        background-color: #38c172;
    }
    
    /* BOTÓN AGREGAR */
    .btn-agregar {
        background-color: #3490dc;
        border: none;
        color: white;
        font-weight: 500;
        padding: 8px 15px;
        border-radius: 3px;
    }
    
    /* PAGINACIÓN */
    .pagination > li > a {
        color: #3490dc;
        border: 1px solid #dae1e7;
    }
    
    .pagination > li.active > a {
        background-color: #3490dc;
        border-color: #3490dc;
    }
    
    /* RESPONSIVO */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        .filtros-container .row > div {
            margin-bottom: 15px;
        }
    }
</style>

@section('contenido')
<div class="content-wrapper" style="background-color: #f5f9fd;">
    <section class="content-header" style="padding: 20px 0; margin-bottom: 30px; border-bottom: 2px solid #e0e9f5;">
        <h1 style="font-size: 2.5rem; color: #2c3e50; margin: 0; font-weight: 700;">
            <span style="color: #3490dc;">Usuarios</span> 
            <small style="font-size: 4.2rem; color: #7f8c8d; display: block; margin-top: 10px;">Gestión de usuarios del sistema</small>
        </h1>
    </section>
    <section class="content">
        <!-- Filtros -->
        <div class="filtros-container">
            <h4 class="filtros-title"><i class="fa fa-filter"></i> Filtros</h4>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Rol</label>
                        <select class="form-control input-sm filtro" id="filtroRol">
                            <option value="">Todos los roles</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Encargado">Encargado</option>
                            <option value="Vendedor">Vendedor</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sucursal</label>
                        <select class="form-control input-sm filtro" id="filtroSucursal">
                            <option value="">Todas las sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Estado</label>
                        <select class="form-control input-sm filtro" id="filtroEstado">
                            <option value="">Todos los estados</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" id="filtroBusqueda" placeholder="Nombre o email...">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" id="btnLimpiarFiltros">
                                    <i class="fa fa-undo"></i> Limpiar
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-agregar" data-toggle="modal" data-target="#modalAgregarUsuario">
                    <i class="fa fa-plus"></i> Agregar Usuario
                </button>
                <div class="contador-usuarios pull-right">
                    Mostrando {{ $usuarios->count() }} de {{ $usuarios->total() }} usuarios
                </div>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="tablaUsuarios">
                        <thead>
                            <tr>
                                <th style="width: 10px;">#</th>  
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Foto</th>
                                <th>Sucursal</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Último login</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($usuarios as $key => $user)
                                <tr>
                                    <td>{{ ($usuarios->currentPage() - 1) * $usuarios->perPage() + $key + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>

                                    <td>
                                        @if($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" class="img-thumbnail" width="40">
                                        @else
                                            <img src="{{ asset('storage/users/anonymus.png') }}" class="img-thumbnail" width="40">
                                        @endif
                                    </td>

                                    <td data-sucursal-id="{{ $user->id_sucursal }}">{{ $user->sucursal->nombre ?? ' ' }}</td>
                                    <td>{{ $user->rol }}</td>

                                    <td>
                                        @if($user->estado == 1)
                                            <button class="btn btn-success btn-xs btnEstadoUser" Uid="{{ $user->id }}" estado="1">Activo</button>
                                        @else
                                           <button class="btn btn-danger btn-xs btnEstadoUser" Uid="{{ $user->id }}" estado="0">Inactivo</button>
                                        @endif
                                    </td>

                                    <td>{{ $user->ultimo_login}}</td>
                                    <td>
                                        <button class="btn btn-warning btn-xs btn-action btnEditarUsuario" idUsuario="{{ $user->id }}" 
                                          data-toggle="modal" data-target="#modalEditarUsuario">
                                          <i class="fa fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger btn-xs btn-action btnEliminarUsuario" idUsuario="{{ $user->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="box-footer clearfix">
                <div class="pull-right">
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Agregar Usuario -->
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Crear Usuario</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action=" " method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" name="name" class="form-control input-lg" placeholder="Nombre" required>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-at"></i></span>
              <input type="email" name="email" class="form-control input-lg" placeholder="Email" required>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
              <input type="password" name="password" class="form-control input-lg" placeholder="Contraseña" required>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-users"></i></span>
              <select name="rol" class="form-control input-lg selectRol" required>
                <option value="">Seleccionar Rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Encargado">Encargado</option>
                <option value="Vendedor">Vendedor</option>
              </select>
            </div>
          </div>
          <div class="form-group selectSucursal">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-building"></i></span>
              <select name="id_sucursal" class="form-control input-lg">
                <option value="">Seleccionar Sucursal</option>
                @foreach ($sucursales as $sucursal)
                  <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditarUsuario">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formEditarUsuario" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h4 class="modal-title">Editar Usuario</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control input-lg" id="nameEditar" name="name" required>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-at"></i></span>
              <input type="email" class="form-control input-lg" id="emailEditar" name="email" required>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-users"></i></span>
              <select name="rol" id="rolEditar" class="form-control input-lg selectRol" required>
                <option value="">Seleccionar Rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Encargado">Encargado</option>
                <option value="Vendedor">Vendedor</option>
              </select>
            </div>
          </div>
          <div class="form-group selectSucursal">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-building"></i></span>
              <select name="id_sucursal" id="sucursalEditar" class="form-control input-lg">
                <option value="">Seleccionar Sucursal</option>
                @foreach ($sucursales as $sucursal)
                  <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Función para aplicar filtros
    function aplicarFiltros() {
        var filtroRol = $('#filtroRol').val().toLowerCase();
        var filtroSucursal = $('#filtroSucursal').val();
        var filtroEstado = $('#filtroEstado').val();
        var filtroBusqueda = $('#filtroBusqueda').val().toLowerCase();
        
        var usuariosVisibles = 0;
        var totalUsuarios = {{ $usuarios->total() }};
        
        $('#tablaUsuarios tbody tr').each(function() {
            var rol = $(this).find('td:eq(5)').text().toLowerCase();
            var sucursalId = $(this).find('td:eq(4)').data('sucursal-id') || '';
            var estado = $(this).find('.btnEstadoUser').attr('estado');
            var nombre = $(this).find('td:eq(1)').text().toLowerCase();
            var email = $(this).find('td:eq(2)').text().toLowerCase();
            
            var coincide = true;
            
            if (filtroRol && rol !== filtroRol.toLowerCase()) coincide = false;
            if (filtroSucursal && sucursalId != filtroSucursal) coincide = false;
            if (filtroEstado && estado != filtroEstado) coincide = false;
            if (filtroBusqueda && !nombre.includes(filtroBusqueda) && !email.includes(filtroBusqueda)) coincide = false;
            
            if (coincide) {
                $(this).show();
                usuariosVisibles++;
            } else {
                $(this).hide();
            }
        });
        
        $('.contador-usuarios').text('Mostrando ' + usuariosVisibles + ' de ' + totalUsuarios + ' usuarios');
    }
    
    // Aplicar filtros
    $('.filtro, #filtroBusqueda').on('change keyup', aplicarFiltros);
    
    // Limpiar filtros
    $('#btnLimpiarFiltros').click(function() {
        $('.filtro').val('');
        $('#filtroBusqueda').val('');
        aplicarFiltros();
    });

    // Cambiar estado usuario
    $(".table").on('click', '.btnEstadoUser', function(){
        var Uid = $(this).attr('Uid');
        var estadoActual = $(this).attr('estado');
        var nuevoEstado = estadoActual == '1' ? '0' : '1';
        var button = $(this);

        Swal.fire({
            title: '¿Cambiar estado?',
            text: "¿Estás seguro de cambiar el estado de este usuario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'Cambiar-Estado-Usuario/' + Uid + '/' + nuevoEstado,
                    type: 'GET',
                    success: function(){
                        if(nuevoEstado == '1') {
                            button.removeClass('btn-danger')
                                  .addClass('btn-success')
                                  .attr('estado', '1')
                                  .text('Activo');
                        } else {
                            button.removeClass('btn-success')
                                  .addClass('btn-danger')
                                  .attr('estado', '0')
                                  .text('Inactivo');
                        }
                        aplicarFiltros();
                        Swal.fire('¡Estado cambiado!', '', 'success');
                    }
                });
            }
        });
    });

    // Toggle sucursal según rol
    function toggleSucursal(selectRol) {
        const sucursalDiv = $(selectRol).closest('form').find('.selectSucursal');
        const rol = $(selectRol).val();
        if (rol === 'Administrador') {
            sucursalDiv.hide();
            sucursalDiv.find('select').val('');
        } else {
            sucursalDiv.show();
        }
    }

    // Inicializar selects de rol
    $('.selectRol').each(function() {
        toggleSucursal(this);
        $(this).on('change', function() {
            toggleSucursal(this);
        });
    });

    // Editar usuario
    $(".table").on('click', '.btnEditarUsuario', function() {
        var Uid = $(this).attr('idUsuario');

        $.ajax({
            url: 'Editar-Usuario/' + Uid,
            type: 'GET',
            success: function(respuesta) {
                $('#nameEditar').val(respuesta.name);
                $('#emailEditar').val(respuesta.email);
                $('#rolEditar').val(respuesta.rol);
                $('#sucursalEditar').val(respuesta.id_sucursal);

                if (respuesta.rol === "Administrador") {
                    $('#sucursalEditar').closest('.selectSucursal').hide();
                } else {
                    $('#sucursalEditar').closest('.selectSucursal').show();
                }

                $('#formEditarUsuario').attr('action', 'Actualizar-Usuario/' + respuesta.id);
            }
        });
    });

    // Eliminar usuario
    $(".table").on('click', '.btnEliminarUsuario', function() {
        var Uid = $(this).attr('idUsuario');

        Swal.fire({
            title: '¿Eliminar usuario?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'Eliminar-Usuario/' + Uid;
            }
        });
    });
});
</script>
@endsection