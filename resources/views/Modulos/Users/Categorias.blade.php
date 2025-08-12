@extends('welcome')

@section('contenido')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-8">
                <h1 class="text-primary" style="font-weight: 600;">
                    <i class="fa fa-tags" style="color: #3c8dbc; margin-right: 10px;"></i> Gestión de Categorías
                </h1>
            </div>
            <div class="col-md-4 text-right">
                <span class="badge bg-blue" style="font-size: 16px; padding: 8px 15px;">
                    <i class="fa fa-info-circle"></i> Total: {{ $categorias->count() }}
                </span>
            </div>
        </div>
        <ol class="breadcrumb" style="background: #f9f9f9; border-radius: 4px; padding: 10px 15px;">
            <li><a href="{{ url('Inicio') }}" style="color: #444;"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active" style="color: #3c8dbc;">Categorías</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
            <div class="box-header with-border" style="background: linear-gradient(to right, #f9f9f9, #e0e0e0);">
                <h3 class="box-title" style="font-weight: 600; color: #444;">
                    <i class="fa fa-list"></i> Listado de Categorías
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarCategoria">
                        <i class="fa fa-plus-circle"></i> Nueva Categoría
                    </button>
                </div>
            </div>
            
            <div class="box-body table-responsive">
                <table class="table table-hover table-striped" id="categorias-table">
                    <thead>
                        <tr class="bg-primary" style="color: white;">
                            <th><i class="fa fa-tag"></i> Nombre</th>
                            <th><i class="fa fa-align-left"></i> Descripción</th>
                            <th><i class="fa fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-sm btnEditar" 
                                            data-id="{{ $categoria->id }}"
                                            data-nombre="{{ $categoria->nombre }}"
                                            data-descripcion="{{ $categoria->descripcion }}"
                                            data-toggle="modal"
                                            data-target="#modalEditarCategoria">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btnEliminarCategoria" idCategoria="{{ $categoria->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
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

<!-- Modal Agregar Categoría -->
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #3c8dbc, #367fa9); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Nueva Categoría</h4>
            </div>
            <form id="formAgregarCategoria" method="POST" action="{{ route('categorias.store') }}">
                @csrf
                <div class="modal-body" style="background: #f9f9f9;">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
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

<!-- Modal Editar Categoría -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, #3c8dbc, #367fa9); color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Categoría</h4>
            </div>
            <form id="formEditarCategoria" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="background: #f9f9f9;">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3"></textarea>
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
    .table-hover tbody tr:hover {
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    
    .btn-group .btn {
        margin-right: 5px;
        border-radius: 4px !important;
    }
</style>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Configuración CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Editar categoría (modal)
    $(document).on('click', '.btnEditar', function() {
        const categoria = {
            id: $(this).data('id'),
            nombre: $(this).data('nombre'),
            descripcion: $(this).data('descripcion')
        };
        
        $('#edit_nombre').val(categoria.nombre);
        $('#edit_descripcion').val(categoria.descripcion);
        
        $('#formEditarCategoria').attr('action', '/categorias/' + categoria.id);
    });

    // Eliminar categoría
    $(document).on('click', '.btnEliminarCategoria', function() {
        var idCat = $(this).attr('idCategoria');

        Swal.fire({
            title: '¿Seguro que deseas eliminar esta categoría?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/Eliminar-Categoria/' + idCat;
            }
        });
    });
});
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif
@endsection