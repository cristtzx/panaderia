@extends('welcome2')

@section('contenido')
<div class="content-wrapper" style="background-color: #f5f9ff;">
    <!-- Encabezado -->
    <section class="content-header" style="padding: 20px 30px 0; background-color: #f1f8e9;">
        <div class="row">
            <div class="col-md-8">
                <h1 style="margin: 0; font-size: 36px; font-weight: bold; color: #2e7d32;">
                    Gestión de Productos
                </h1>
            </div>
            <div class="col-md-4 text-right">
                <ol class="breadcrumb" style="background: transparent; margin-bottom: 0; padding-top: 10px;">
                    <li>
                        <i class="fas fa-tachometer-alt"></i> <a href="{{ url('Inicio') }}" style="color: #689f38;">Inicio</a>
                    </li>
                    <li class="active" style="color: #2e7d32;">Productos</li>
                </ol>
            </div>
        </div>
        <hr style="border-color: #a5d6a7; margin-top: 10px; margin-bottom: 0;">
    </section>

    <!-- Contenido -->
    <section class="content" style="padding: 20px 30px;">
        <div class="row mb-3">
            <div class="col-md-6">
                <h4 style="margin: 0; font-weight: bold; color: #424242;">
                    <i class="fas fa-list-ul mr-2"></i>Listado de Productos
                </h4>
            </div>
            <div class="col-md-6 text-right">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCrearProducto">
                        <i class="fa fa-plus-circle"></i> Nuevo Producto
                    </button>
                    
                    <div class="btn-group" role="group">
                        <button id="btnReportes" type="button" class="btn btn-danger btn-sm dropdown-toggle" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="font-weight: 500;">
                            <i class="fas fa-file-alt mr-1"></i> Reportes
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnReportes">
                            <a class="dropdown-item" href="{{ route('reporte.inventario') }}">
                                <i class="fas fa-boxes mr-2"></i> Inventario General
                            </a>
                            <a class="dropdown-item" href="{{ route('reporte.caducidad') }}">
                                <i class="fas fa-clock mr-2"></i> Productos por Caducar
                            </a>
                            @if(auth()->user()->rol === 'Administrador')
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('reporte.lotes') }}">
                                    <i class="fas fa-history mr-2"></i> Historial de Lotes
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div style="overflow-x: auto;">
            <table id="tablaProductos" class="table table-hover" style="width: 100%; margin-bottom: 1rem; background-color: white; border-radius: 4px; overflow: hidden;">
                <thead style="background-color: #388e3c; color: white;">
                    <tr>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Código</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Nombre</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Categoría</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Receta</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Precio</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Stock</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Caducidad</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Imagen</th>
                        <th style="padding: 12px 15px; border-bottom: 2px solid #2e7d32;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr style="transition: all 0.2s; border-bottom: 1px solid #e8f5e9;">
                        <td style="padding: 12px 15px;">
                            <span class="badge" style="background-color: #81c784; color: #1b5e20;">{{ $producto->codigo }}</span>
                        </td>
                        <td style="padding: 12px 15px;">
                            <strong>{{ $producto->nombre }}</strong>
                        </td>
                        <td style="padding: 12px 15px;">
                            <span class="badge" style="background-color: #a5d6a7; color: #1b5e20;">{{ $producto->categoria->nombre }}</span>
                        </td>
                        <td style="padding: 12px 15px;">
                            @if($producto->receta)
                                <span class="badge" style="background-color: #66bb6a; color: white;">{{ $producto->receta->nombre }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td style="padding: 12px 15px; text-align: right; font-weight: 500; color: #2e7d32;">
                            ${{ number_format($producto->precio_venta, 2) }}
                        </td>
                        <td style="padding: 12px 15px;">
                            @if($producto->stock <= 5)
                                <span class="badge" style="background-color: #e53935; color: white;">{{ $producto->stock }}</span>
                            @elseif($producto->stock <= 15)
                                <span class="badge" style="background-color: #fb8c00; color: white;">{{ $producto->stock }}</span>
                            @else
                                <span class="badge" style="background-color: #43a047; color: white;">{{ $producto->stock }}</span>
                            @endif
                        </td>
                        <td style="padding: 12px 15px;">
                            @if($producto->maneja_lotes && $producto->lotes->isNotEmpty())
                                @php
                                    $loteUrgente = $producto->lotes->sortBy('fecha_caducidad')->first();
                                    $hoy = now()->startOfDay();
                                    $fechaCaducidad = $loteUrgente->fecha_caducidad->startOfDay();
                                    $diasRestantes = $hoy->diffInDays($fechaCaducidad, false);
                                    
                                    if ($diasRestantes < 0) {
                                        $claseColor = 'bg-red-500';
                                        $estado = 'Caducado';
                                    } elseif ($diasRestantes == 0) {
                                        $claseColor = 'bg-red-500';
                                        $estado = 'Hoy';
                                    } elseif ($diasRestantes <= 2) {
                                        $claseColor = 'bg-yellow-500';
                                        $estado = $diasRestantes.' días';
                                    } else {
                                        $claseColor = 'bg-green-500';
                                        $estado = 'Vigente';
                                    }
                                @endphp
                                
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $claseColor }} mr-2" style="color: white;">{{ $estado }}</span>
                                    <small>{{ $loteUrgente->fecha_caducidad->format('d/m/Y') }}</small>
                                </div>
                                
                                @if($producto->lotes->count() > 1)
                                    <button class="btn btn-xs btn-link p-0 mt-1" data-toggle="collapse" data-target="#lotes-{{ $producto->id }}" style="color: #2e7d32;">
                                        <small><i class="fas fa-chevron-down mr-1"></i>Ver todos ({{ $producto->lotes->count() }})</small>
                                    </button>
                                    <div id="lotes-{{ $producto->id }}" class="collapse mt-2" style="background-color: #e8f5e9; padding: 8px; border-radius: 4px;">
                                        @foreach($producto->lotes->sortBy('fecha_caducidad') as $lote)
                                            @php
                                                $fechaCad = $lote->fecha_caducidad->startOfDay();
                                                $dias = $hoy->diffInDays($fechaCad, false);
                                                $clase = $dias < 0 ? 'text-red-500' : ($dias <= 2 ? 'text-yellow-500' : 'text-green-500');
                                            @endphp
                                            <div class="small mb-1" style="color: {{ $clase == 'text-red-500' ? '#e53935' : ($clase == 'text-yellow-500' ? '#fb8c00' : '#43a047') }};">
                                                {{ $lote->cantidad }}u - {{ $lote->fecha_caducidad->format('d/m/Y') }}
                                                @if($dias < 0)(Caducado)@elseif($dias == 0)(Hoy)@else({{ $dias }} días)@endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td style="padding: 12px 15px; text-align: center;">
                            @if($producto->imagen)
                                <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" 
                                     style="max-height: 50px; max-width: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #c8e6c9;">
                            @else
                                <span class="text-muted"><i class="fas fa-image"></i></span>
                            @endif
                        </td>
                        <td style="padding: 12px 15px; text-align: center;">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-success btnEditarProducto" data-id="{{ $producto->id }}" 
                                        data-toggle="modal" data-target="#modalEditarProducto" title="Editar"
                                        style="border-radius: 4px 0 0 4px; border-color: #43a047; color: #43a047;">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btnEliminarProducto" data-id="{{ $producto->id }}" 
                                        title="Eliminar" style="border-radius: 0 4px 4px 0; border-color: #e53935; color: #e53935;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Modal Crear Producto -->
<div class="modal fade" id="modalCrearProducto" tabindex="-1" role="dialog" aria-labelledby="modalCrearProductoLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background-color: #4caf50; color: white; padding: 15px 20px;">
                <h4 class="modal-title" style="font-weight: 500; margin: 0;">
                    <i class="fas fa-plus-circle mr-2"></i>Nuevo Producto
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCrearProducto" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 20px;">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_categoria" class="font-weight-bold" style="color: #2e7d32;">Categoría *</label>
                                <select class="form-control" id="id_categoria" name="id_categoria" required 
                                        style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                                    <option value="">Seleccione categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="codigo" class="font-weight-bold" style="color: #2e7d32;">Código del Producto *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="codigo" name="codigo" readonly required
                                           style="border-radius: 4px 0 0 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="btnGenerarCodigo"
                                                style="border-radius: 0 4px 4px 0; border-color: #c8e6c9; color: #2e7d32;">
                                            <i class="fas fa-sync-alt mr-1"></i> Generar
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Seleccione una categoría primero</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold" style="color: #2e7d32;">Nombre del Producto *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required
                                       style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                            </div>
                            
                            <div class="form-group">
                                <label for="id_recetas" style="color: #2e7d32;">Receta Asociada</label>
                                <select class="form-control" id="id_recetas" name="id_recetas"
                                        style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                                    <option value="">Sin receta</option>
                                    @foreach($recetas as $receta)
                                        <option value="{{ $receta->id }}">{{ $receta->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="maneja_lotes" value="0">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="maneja_lotes" name="maneja_lotes" value="1">
                                    <label class="custom-control-label" for="maneja_lotes" style="color: #2e7d32;">Control por lotes (caducidad)</label>
                                </div>
                            </div>
                            
                            <div class="form-group" id="dias_caducidad_group" style="display: none;">
                                <label for="dias_caducidad" class="font-weight-bold" style="color: #2e7d32;">Días para caducidad *</label>
                                <input type="number" class="form-control" id="dias_caducidad" name="dias_caducidad" min="1" value="7" required
                                       style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                            </div>
                            
                            <div class="form-group">
                                <label for="stock" class="font-weight-bold" style="color: #2e7d32;">Stock Inicial *</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="0" min="0" required
                                       style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                            </div>
                            
                            <div class="form-group">
                                <label for="precio_estimado" style="color: #2e7d32;">Precio Estimado ($)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-right: none;">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="precio_estimado" name="precio_estimado" min="0"
                                           style="border-radius: 0 4px 4px 0; padding: 8px 12px; border: 1px solid #c8e6c9; border-left: none;">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="calcular_ganancia" name="calcular_ganancia">
                                    <label class="custom-control-label" for="calcular_ganancia" style="color: #2e7d32;">Calcular ganancia automática</label>
                                </div>
                                <div id="ganancia_group" style="display: none;">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="porcentaje_ganancia" name="porcentaje_ganancia" min="0" max="1000" value="20"
                                               style="border-radius: 4px 0 0 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-left: none;">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Porcentaje sobre el precio estimado</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="precio_venta" class="font-weight-bold" style="color: #2e7d32;">Precio de Venta ($) *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-right: none;">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" min="0" required
                                           style="border-radius: 0 4px 4px 0; padding: 8px 12px; border: 1px solid #c8e6c9; border-left: none;">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="imagen" style="color: #2e7d32;">Imagen del Producto</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imagen" name="imagen" accept="image/*">
                                    <label class="custom-file-label" for="imagen" style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">Seleccionar archivo</label>
                                </div>
                                <small class="form-text text-muted">Formatos: JPG, PNG, JPEG. Máx. 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #c8e6c9;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="font-weight: 500; padding: 8px 16px; border-radius: 4px; background-color: #a5d6a7; border-color: #a5d6a7; color: #1b5e20;">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success"
                            style="font-weight: 500; padding: 8px 16px; border-radius: 4px; background-color: #2e7d32; border-color: #2e7d32;">
                        <i class="fas fa-save mr-1"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="modalEditarProductoLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background-color: #689f38; color: #fff; padding: 15px 20px;">
                <h4 class="modal-title" style="font-weight: 500; margin: 0;">
                    <i class="fas fa-edit mr-2"></i>Editar Producto
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarProducto" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 20px;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="maneja_lotes" value="0">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="edit_maneja_lotes" name="maneja_lotes" value="1">
                                    <label class="custom-control-label" for="edit_maneja_lotes" style="color: #2e7d32;">Control por lotes (caducidad)</label>
                                </div>
                            </div>
                            
                            <div class="form-group" id="edit_dias_caducidad_group" style="display: none;">
                                <label for="edit_dias_caducidad" class="font-weight-bold" style="color: #2e7d32;">Días para caducidad *</label>
                                <input type="number" class="form-control" id="edit_dias_caducidad" name="dias_caducidad" min="1" required
                                       style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_nombre" class="font-weight-bold" style="color: #2e7d32;">Nombre del Producto *</label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required
                                       style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_id_categoria" class="font-weight-bold" style="color: #2e7d32;">Categoría *</label>
                                <select class="form-control" id="edit_id_categoria" name="id_categoria" required
                                        style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                                    <!-- Se llena con JavaScript -->
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_id_recetas" style="color: #2e7d32;">Receta Asociada</label>
                                <select class="form-control" id="edit_id_recetas" name="id_recetas"
                                        style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                                    <!-- Se llena con JavaScript -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_stock" class="font-weight-bold" style="color: #2e7d32;">Stock Actual *</label>
                                <input type="number" class="form-control" id="edit_stock" name="stock" min="0" required
                                       style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_precio_estimado" style="color: #2e7d32;">Precio Estimado ($)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-right: none;">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="edit_precio_estimado" name="precio_estimado" min="0"
                                           style="border-radius: 0 4px 4px 0; padding: 8px 12px; border: 1px solid #c8e6c9; border-left: none;">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="edit_calcular_ganancia" name="calcular_ganancia">
                                    <label class="custom-control-label" for="edit_calcular_ganancia" style="color: #2e7d32;">Calcular ganancia automática</label>
                                </div>
                                <div id="edit_ganancia_group" style="display: none;">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="edit_porcentaje_ganancia" name="porcentaje_ganancia" min="0" max="1000" value="20"
                                               style="border-radius: 4px 0 0 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-left: none;">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Porcentaje sobre el precio estimado</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_precio_venta" class="font-weight-bold" style="color: #2e7d32;">Precio de Venta ($) *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-right: none;">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="edit_precio_venta" name="precio_venta" min="0" required
                                           style="border-radius: 0 4px 4px 0; padding: 8px 12px; border: 1px solid #c8e6c9; border-left: none;">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_imagen" style="color: #2e7d32;">Imagen del Producto</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="edit_imagen" name="imagen" accept="image/*">
                                    <label class="custom-file-label" for="edit_imagen" style="border-radius: 4px; padding: 8px 12px; border: 1px solid #c8e6c9;">Cambiar imagen</label>
                                </div>
                                <small class="form-text text-muted">Dejar en blanco para mantener la imagen actual</small>
                                <div id="imagen_actual_container" class="mt-2">
                                    <small>Imagen actual:</small>
                                    <img id="imagen_actual" src="" class="img-thumbnail" style="max-height: 100px; max-width: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #c8e6c9;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #c8e6c9;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="font-weight: 500; padding: 8px 16px; border-radius: 4px; background-color: #a5d6a7; border-color: #a5d6a7; color: #1b5e20;">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success"
                            style="font-weight: 500; padding: 8px 16px; border-radius: 4px; background-color: #689f38; border-color: #689f38;">
                        <i class="fas fa-save mr-1"></i> Actualizar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Generación de código
    function generarCodigoFormatoFijo(categoriaNombre) {
        const fecha = new Date();
        const dia = String(fecha.getDate()).padStart(2, '0');
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const anio = String(fecha.getFullYear()).slice(-2);
        const prefijo = categoriaNombre.trim().substring(0, 3).toUpperCase();
        const secuencia = Math.floor(Math.random() * 90 + 10);
        return `${prefijo}-${dia}${mes}${anio}-${secuencia}`;
    }

    $('#btnGenerarCodigo').click(function() {
        const categoriaId = $('#id_categoria').val();
        if (!categoriaId) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione categoría',
                text: 'Debe seleccionar una categoría primero',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        const categoriaNombre = $('#id_categoria option:selected').text();
        $('#codigo').val(generarCodigoFormatoFijo(categoriaNombre));
    });

    $('#id_categoria').change(function() {
        if ($(this).val()) {
            $('#btnGenerarCodigo').click();
        }
    });

    // Manejo de lotes
    $('#maneja_lotes, #edit_maneja_lotes').change(function() {
        const isEdit = this.id === 'edit_maneja_lotes';
        const $container = isEdit ? $('#edit_dias_caducidad_group') : $('#dias_caducidad_group');
        $container.toggle(this.checked);
        
        if (this.checked) {
            const $input = isEdit ? $('#edit_dias_caducidad') : $('#dias_caducidad');
            if (!$input.val() || parseInt($input.val()) < 1) {
                $input.val(7);
            }
        }
    }).trigger('change');

    // Calculadora de ganancia (Crear Producto)
    $('#calcular_ganancia').change(function() {
        $('#ganancia_group').toggle(this.checked);
        if (this.checked) {
            calcularPrecioVenta();
        }
    });

    $('#porcentaje_ganancia, #precio_estimado').on('input', function() {
        if ($('#calcular_ganancia').is(':checked')) {
            calcularPrecioVenta();
        }
    });

    // Calculadora de ganancia (Editar Producto)
    $('#edit_calcular_ganancia').change(function() {
        $('#edit_ganancia_group').toggle(this.checked);
        if (this.checked) {
            calcularPrecioVentaEdicion();
        }
    });

    $('#edit_porcentaje_ganancia, #edit_precio_estimado').on('input', function() {
        if ($('#edit_calcular_ganancia').is(':checked')) {
            calcularPrecioVentaEdicion();
        }
    });

    function calcularPrecioVenta() {
        const precioEstimado = parseFloat($('#precio_estimado').val()) || 0;
        const porcentaje = parseFloat($('#porcentaje_ganancia').val()) || 0;
        const precioVenta = precioEstimado * (1 + (porcentaje / 100));
        
        $('#precio_venta').val(precioVenta.toFixed(2));
    }

    function calcularPrecioVentaEdicion() {
        const precioEstimado = parseFloat($('#edit_precio_estimado').val()) || 0;
        const porcentaje = parseFloat($('#edit_porcentaje_ganancia').val()) || 0;
        const precioVenta = precioEstimado * (1 + (porcentaje / 100));
        
        $('#edit_precio_venta').val(precioVenta.toFixed(2));
    }

    // Validar antes de enviar el formulario
    $('#formCrearProducto, #formEditarProducto').submit(function(e) {
        const $form = $(this);
        const isCrear = $form.is('#formCrearProducto');
        const manejaLotes = isCrear ? $('#maneja_lotes').is(':checked') : $('#edit_maneja_lotes').is(':checked');
        
        if (manejaLotes) {
            const diasInput = isCrear ? $('#dias_caducidad') : $('#edit_dias_caducidad');
            const dias = parseInt(diasInput.val());
            
            if (isNaN(dias) || dias < 1) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Días inválidos',
                    text: 'Los días de caducidad deben ser un número mayor a 0',
                    confirmButtonColor: '#2e7d32'
                });
                return false;
            }
        }
        return true;
    });

    // Editar Producto
    $(document).on('click', '.btnEditarProducto', function() {
        const id = $(this).data('id');
        $.get(`Editar-Producto/${id}`, function(data) {
            const producto = data.producto;
            
            $('#edit_id').val(producto.id);
            $('#edit_nombre').val(producto.nombre);
            $('#edit_stock').val(producto.stock);
            $('#edit_precio_venta').val(producto.precio_venta);
            $('#edit_precio_estimado').val(producto.precio_estimado);
            
            // Cargar porcentaje de ganancia si existe
            if (producto.porcentaje_ganancia) {
                $('#edit_porcentaje_ganancia').val(producto.porcentaje_ganancia);
                $('#edit_calcular_ganancia').prop('checked', true).trigger('change');
            }
            
            $('#edit_maneja_lotes').prop('checked', producto.maneja_lotes).trigger('change');
            if (producto.maneja_lotes) {
                $('#edit_dias_caducidad').val(producto.dias_caducidad || 7);
            }
            
            if (producto.imagen) {
                $('#imagen_actual').attr('src', `/${producto.imagen}`);
                $('#imagen_actual_container').show();
            } else {
                $('#imagen_actual_container').hide();
            }
            
            // Llenar categorías
            $('#edit_id_categoria').empty();
            data.categorias.forEach(categoria => {
                $('#edit_id_categoria').append(new Option(
                    categoria.nombre, 
                    categoria.id, 
                    false, 
                    categoria.id === producto.id_categoria
                ));
            });
            
            // Llenar recetas
            $('#edit_id_recetas').empty().append('<option value="">Sin receta</option>');
            data.recetas.forEach(receta => {
                $('#edit_id_recetas').append(new Option(
                    receta.nombre, 
                    receta.id, 
                    false, 
                    receta.id === producto.id_recetas
                ));
            });
            
            $('#formEditarProducto').attr('action', `Actualizar-Producto/${producto.id}`);
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar la información del producto',
                confirmButtonColor: '#2e7d32'
            });
        });
    });

    // Formulario Crear
    $('#formCrearProducto').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        if ($('#maneja_lotes').is(':checked') && (!$('#dias_caducidad').val() || parseInt($('#dias_caducidad').val()) < 1)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe especificar un número válido de días para caducidad',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        
        $.ajax({
            url: 'Crear-Producto',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#modalCrearProducto').find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');
            },
            success: function(response) {
                $('#modalCrearProducto').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.success,
                    confirmButtonColor: '#2e7d32'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'Error al crear producto',
                    confirmButtonColor: '#2e7d32'
                });
            },
            complete: function() {
                $('#modalCrearProducto').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Producto');
            }
        });
    });

    // Formulario Editar
    $('#formEditarProducto').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        if ($('#edit_maneja_lotes').is(':checked') && (!$('#edit_dias_caducidad').val() || parseInt($('#edit_dias_caducidad').val()) < 1)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe especificar un número válido de días para caducidad',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#modalEditarProducto').find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Actualizando...');
            },
            success: function(response) {
                $('#modalEditarProducto').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.success,
                    confirmButtonColor: '#2e7d32'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'Error al actualizar producto',
                    confirmButtonColor: '#2e7d32'
                });
            },
            complete: function() {
                $('#modalEditarProducto').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Actualizar Producto');
            }
        });
    });

    // Eliminar Producto
    $(document).on('click', '.btnEliminarProducto', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: '¿Eliminar producto?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53935',
            cancelButtonColor: '#a5d6a7',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `Eliminar-Producto/${id}`,
                    type: 'GET',
                    beforeSend: function() {
                        $('.btnEliminarProducto[data-id="'+id+'"]').html('<i class="fas fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.success,
                            confirmButtonColor: '#2e7d32'
                        }).then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el producto',
                            confirmButtonColor: '#2e7d32'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection