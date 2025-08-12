@extends('welcome2')

@section('contenido')
    <div class="content-wrapper">
        <section class="content-header">
            <h1 style="color: #0073b7;">Gestión de Ventas</h1>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box" style="border-top: 3px solid #0073b7;">
                        <div class="box-header">
                            <h3 class="box-title" style="color: #0073b7;">
                                <i class="fas fa-cash-register"></i> Registro de Ventas
                            </h3>
                            <button class="btn pull-right" style="background-color: #0073b7; color: white;" 
                                    data-toggle="modal" data-target="#modalAgregarVenta">
                                <i class="fas fa-plus"></i> Nueva Venta
                            </button>
                        </div>
                        
                        <div class="box-body">
                            <table id="tabla-ventas" class="table table-bordered">
                                <thead>
                                    <tr style="background-color: #f8f9fa; color: #6c757d;">
                                        <th style="border-bottom: 2px solid #0073b7;">ID</th>
                                        <th style="border-bottom: 2px solid #0073b7;">Fecha</th>
                                        <th style="border-bottom: 2px solid #0073b7;">Total</th>
                                        <th style="border-bottom: 2px solid #0073b7;">Método Pago</th>
                                        <th style="border-bottom: 2px solid #0073b7;">Estado</th>
                                        <th style="border-bottom: 2px solid #0073b7;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventas as $venta)
                                        <tr>
                                            <td>{{ $venta->id }}</td>
                                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                            <td style="font-weight: bold; color: #0073b7;">${{ number_format($venta->total, 2) }}</td>
                                            <td>
                                                @if($venta->metodo_pago == 'tarjeta')
                                                    Transferencia
                                                @elseif($venta->metodo_pago == 'terminal')
                                                    Terminal Punto de Venta
                                                @else
                                                    {{ ucfirst($venta->metodo_pago) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($venta->estado == 'completada')
                                                    <span class="label" style="background-color: #28a745; color: white;">Completada</span>
                                                @else
                                                    <span class="label" style="background-color: #dc3545; color: white;">Cancelada</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($venta->estado == 'completada')
                                                    <button class="btn btn-xs" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #6c757d;" 
                                                            data-toggle="modal" data-target="#modalCancelarVenta" 
                                                            data-venta-id="{{ $venta->id }}">
                                                        <i class="fas fa-ban"></i> Cancelar
                                                    </button>
                                                    <a href="{{ route('ventas.ticket', ['venta' => $venta->id]) }}" 
                                                       class="btn btn-xs" 
                                                       style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #6c757d;"
                                                       target="_blank">
                                                        <i class="fas fa-receipt"></i> Ticket
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal Cancelar Venta -->
<div class="modal fade" id="modalCancelarVenta" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid #dc3545;">
                <h4 class="modal-title" style="color: #dc3545;">
                    <i class="fas fa-ban"></i> Cancelar Venta
                </h4>
            </div>
            <form method="POST" action="" id="formCancelarVenta">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label style="color: #6c757d;">Motivo de cancelación</label>
                        <select class="form-control" name="motivo_cancelacion_id" id="selectMotivo" required>
                            <option value="">Seleccione un motivo</option>
                            @foreach($motivosCancelacion as $motivo)
                                <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                            @endforeach
                            <option value="otro">Otro (especifique)</option>
                        </select>
                    </div>
                    
                    <!-- Campo de texto adicional que solo aparece cuando selecciona "Otro" -->
                    <div class="form-group" id="otroMotivoContainer" style="display: none; margin-top: 15px;">
                        <label style="color: #6c757d;">Especifique el motivo</label>
                        <textarea class="form-control" name="motivo_adicional" id="motivoAdicional" 
                                  rows="3" placeholder="Describa el motivo de cancelación"></textarea>
                        <small class="text-muted">Este motivo se guardará junto con el seleccionado</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #6c757d;" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn" style="background-color: #dc3545; color: white;">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Modal Agregar Venta -->
    <div class="modal fade" id="modalAgregarVenta" tabindex="-1" role="dialog" aria-labelledby="modalAgregarVentaLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 2px solid #0073b7;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalAgregarVentaLabel" style="color: #0073b7;">
                        <i class="fas fa-cash-register"></i> Nueva Venta
                    </h4>
                </div>
                <form id="formAgregarVenta" method="POST" action="{{ route('ventas.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #6c757d;">Sucursal</label>
                                    <select class="form-control" name="sucursales_id" required>
                                        @foreach($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #6c757d;">Método de Pago</label>
                                    <select class="form-control" name="metodo_pago" id="metodoPago" required>
                                        <option value="">Seleccione...</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Transferencia</option>
                                        <option value="terminal">Terminal Punto de Venta</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Campos para Efectivo -->
                        <div class="row" id="efectivoFields" style="display: none; margin-top: 15px; padding: 15px; background-color: #f8f9fa; border-radius: 4px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #6c757d;">Efectivo Recibido</label>
                                    <input type="number" class="form-control" id="efectivoRecibido" step="0.01" min="0" oninput="calcularCambio()">
                                    <input type="hidden" name="efectivo_recibido" id="efectivoRecibidoHidden">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #6c757d;">Cambio</label>
                                    <input type="text" class="form-control" id="cambio" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Campos para Terminal -->
                        <div class="row" id="terminalFields" style="display: none; margin-top: 15px; padding: 15px; background-color: #f8f9fa; border-radius: 4px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #6c757d;">Referencia de Pago</label>
                                    <input type="text" class="form-control" name="referencia_pago" placeholder="Número de transacción" required>
                                </div>
                            </div>
                        </div>

                        <!-- Campos para Transferencia (tarjeta) -->
                        <div class="row" id="transferenciaFields" style="display: none; margin-top: 15px; padding: 15px; background-color: #f8f9fa; border-radius: 4px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #6c757d;">Código de Transacción</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="codigo_transferencia" id="codigoTransferencia" placeholder="Código de transacción" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="verificarPagoMP">
                                                <i class="fas fa-sync-alt"></i> Verificar Pago
                                            </button>
                                        </span>
                                    </div>
                                    <small class="text-muted">Este código lo proporciona Mercado Pago (ej: MP123456789)</small>
                                    <div id="pagoInfo" class="mt-2" style="display: none;">
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> <strong>Pago verificado</strong>
                                            <div id="pagoDetalles"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selección de Productos -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #6c757d;">Productos</label>
                                    <select class="form-control" id="selectProducto">
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" 
                                                    data-codigo="{{ $producto->codigo }}"
                                                    data-precio="{{ $producto->precio_venta }}"
                                                    data-stock="{{ $producto->stock }}"
                                                    data-nombre="{{ $producto->nombre }}">
                                                {{ $producto->codigo }} - {{ $producto->nombre }} (${{ number_format($producto->precio_venta, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Productos -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tablaProductos" style="margin-top: 20px;">
                                        <thead>
                                            <tr style="background-color: #e9ecef;">
                                                <th>Código</th>
                                                <th>Producto</th>
                                                <th width="15%">Cantidad</th>
                                                <th width="15%">P. Unitario</th>
                                                <th width="15%">Subtotal</th>
                                                <th width="10%">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas de productos se agregarán aquí -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-right">TOTAL:</th>
                                                <th id="totalVenta">$0.00</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #6c757d;" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn" style="background-color: #0073b7; color: white;">Registrar Venta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cancelar Venta -->
    <div class="modal fade" id="modalCancelarVenta" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 2px solid #dc3545;">
                    <h4 class="modal-title" style="color: #dc3545;">
                        <i class="fas fa-ban"></i> Cancelar Venta
                    </h4>
                </div>
                <form method="POST" action="" id="formCancelarVenta">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="color: #6c757d;">Motivo de cancelación</label>
                            <select class="form-control" name="motivo_cancelacion_id" required>
                                @foreach($motivosCancelacion as $motivo)
                                    <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #6c757d;" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn" style="background-color: #dc3545; color: white;">Confirmar Cancelación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts necesarios -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        // Array para rastrear productos agregados
        let productosAgregados = [];
        
        $(document).ready(function() {
            // Mostrar campos según método de pago
            $('#metodoPago').on('change', function() {
                const metodo = $(this).val();
                
                // Oculta todos los campos primero
                $('#efectivoFields, #terminalFields, #transferenciaFields').hide();
                
                // Muestra los campos correspondientes
                if (metodo === 'efectivo') {
                    $('#efectivoFields').show();
                } else if (metodo === 'terminal') {
                    $('#terminalFields').show();
                } else if (metodo === 'tarjeta') {
                    $('#transferenciaFields').show();
                }
            });

            // Agregar producto a la tabla
            $('#selectProducto').on('change', function() {
                const productoId = $(this).val();
                if (!productoId) return;

                const productoOption = $(this).find('option:selected');
                const codigoProducto = productoOption.data('codigo');
                const productoNombre = productoOption.data('nombre');
                const precio = parseFloat(productoOption.data('precio'));
                const stock = parseInt(productoOption.data('stock'));
                const idUnico = `prod-${productoId}`;

                // Verificar si el producto ya fue agregado
                if (productosAgregados.includes(productoId)) {
                    // Incrementar cantidad si ya existe
                    const inputCantidad = $(`#${idUnico} .cantidad`);
                    const cantidadActual = parseInt(inputCantidad.val());
                    const nuevaCantidad = cantidadActual + 1;
                    
                    if (nuevaCantidad > stock) {
                        alert('No hay suficiente stock disponible');
                        return;
                    }
                    
                    inputCantidad.val(nuevaCantidad);
                    actualizarSubtotal(idUnico);
                    $(this).val('');
                    return;
                }

                // Agregar nuevo producto a la tabla
                const row = `
                    <tr id="${idUnico}">
                        <td>${codigoProducto}</td>
                        <td>${productoNombre}</td>
                        <td>
                            <input type="number" name="productos[${productoId}][cantidad]" 
                                   class="form-control input-sm cantidad" 
                                   value="1" min="1" max="${stock}" 
                                   onchange="actualizarSubtotal('${idUnico}')">
                        </td>
                        <td>
                            $${precio.toFixed(2)}
                            <input type="hidden" name="productos[${productoId}][precio_unitario]" value="${precio}">
                            <input type="hidden" name="productos[${productoId}][id]" value="${productoId}">
                            <input type="hidden" class="precio" value="${precio}">
                        </td>
                        <td class="subtotal">$${precio.toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger" 
                                    onclick="eliminarProducto('${idUnico}', ${productoId})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                $('#tablaProductos tbody').append(row);
                productosAgregados.push(productoId);
                calcularTotal();
                $(this).val('');
            });

            // Configurar formulario de cancelación
            $('#modalCancelarVenta').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const ventaId = button.data('venta-id');
                const form = $('#formCancelarVenta');
                form.attr('action', `/ventas/${ventaId}/cancelar`);
            });

            // Verificación de pagos con MercadoPago
            $('#verificarPagoMP').click(function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Verificando...');
                
                $.ajax({
                    url: '/mercadopago/verificar-pago',
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.payment.status === 'approved') {
                            const pago = response.payment;
                            $('#codigoTransferencia').val(pago.id);
                            
                            // Mostrar detalles del pago
                            const montoFormateado = new Intl.NumberFormat('es-CO', {
                                style: 'currency',
                                currency: 'COP'
                            }).format(pago.amount);
                            
                            $('#pagoDetalles').html(`
                                <p>Monto: ${montoFormateado}</p>
                                <p>Método: ${pago.method}</p>
                                <p>Fecha: ${new Date(pago.date).toLocaleString()}</p>
                            `);
                            
                            $('#pagoInfo').show();
                            btn.html('<i class="fas fa-check-circle"></i> Verificado');
                            
                            // Verificar si el monto coincide con el total
                            const totalVenta = parseFloat($('#totalVenta').text().replace(/[^0-9.-]+/g,""));
                            if (pago.amount < totalVenta) {
                                alert('El monto del pago es menor al total de la venta');
                            }
                        } else if (response.success) {
                            alert('Pago encontrado pero no está aprobado. Estado: ' + response.payment.status);
                            btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Verificar');
                        } else {
                            alert(response.message || 'No se encontraron pagos recientes');
                            btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Verificar');
                        }
                    },
                    error: function(xhr) {
                        alert('Error al conectar con el servidor');
                        btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Verificar');
                    }
                });
            });

            // Verificación automática cada 30 segundos
            setInterval(function() {
                if ($('#metodoPago').val() === 'tarjeta' && $('#codigoTransferencia').val() === '') {
                    $('#verificarPagoMP').click();
                }
            }, 30000);

            // Manejo del envío del formulario con AJAX
            $('#formAgregarVenta').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const metodoPago = $('#metodoPago').val();
                
                // Validación básica del cliente
                if (productosAgregados.length === 0) {
                    alert('Debe agregar al menos un producto');
                    return false;
                }
                
                // Validar campos según método de pago
                if (metodoPago === 'efectivo') {
                    const efectivo = parseFloat($('#efectivoRecibido').val()) || 0;
                    const total = parseFloat($('#totalVenta').text().replace('$', '').replace(',', ''));
                    
                    if (efectivo < total) {
                        alert('El efectivo recibido debe ser mayor o igual al total');
                        return false;
                    }
                    $('#efectivoRecibidoHidden').val(efectivo);
                } else if (metodoPago === 'terminal') {
                    if (!$('input[name="referencia_pago"]').val()) {
                        alert('Debe ingresar la referencia de pago');
                        return false;
                    }
                } else if (metodoPago === 'tarjeta') {
                    if (!$('input[name="codigo_transferencia"]').val()) {
                        alert('Debe ingresar el código de transferencia');
                        return false;
                    }
                }
                
                // Mostrar carga
                $('.modal-footer button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
                
                // Crear FormData y enviar
                const formData = new FormData(this);
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Mostrar mensaje de éxito
                            alert('Venta registrada correctamente');
                            
                            // Abrir el ticket en una nueva pestaña
                            if (response.ticket_url) {
                                const ticketWindow = window.open(response.ticket_url, '_blank');
                                
                                if (ticketWindow) {
                                    ticketWindow.focus();
                                } else {
                                    alert('El ticket se ha generado pero no se pudo abrir automáticamente. Por favor habilite ventanas emergentes para este sitio.');
                                }
                            }
                            
                            // Cerrar modal y recargar página
                            $('#modalAgregarVenta').modal('hide');
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al procesar la venta';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alert(errorMsg);
                    },
                    complete: function() {
                        $('.modal-footer button[type="submit"]').html('Registrar Venta');
                    }
                });
            });
        });

        function calcularCambio() {
            if ($('#metodoPago').val() === 'efectivo') {
                const efectivo = parseFloat($('#efectivoRecibido').val()) || 0;
                const total = parseFloat($('#totalVenta').text().replace('$', '').replace(',', ''));
                $('#cambio').val('$' + (efectivo - total).toFixed(2));
            }
        }

        function actualizarSubtotal(idUnico) {
            const row = $(`#${idUnico}`);
            const cantidad = parseFloat(row.find('.cantidad').val()) || 0;
            const precio = parseFloat(row.find('.precio').val());
            const subtotal = cantidad * precio;
            row.find('.subtotal').text('$' + subtotal.toFixed(2));
            calcularTotal();
        }

        function eliminarProducto(idUnico, productoId) {
            $(`#${idUnico}`).remove();
            // Remover el producto del array de productos agregados
            productosAgregados = productosAgregados.filter(id => id != productoId);
            calcularTotal();
        }

        function calcularTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).text().replace('$', '').replace(',', ''));
            });
            $('#totalVenta').text('$' + total.toFixed(2));
            
            if ($('#metodoPago').val() === 'efectivo') {
                calcularCambio();
            }
        }


        // Mostrar/ocultar campo de texto para "Otro" motivo
$('#selectMotivo').on('change', function() {
    if ($(this).val() === 'otro') {
        $('#otroMotivoContainer').show();
        $('#motivoAdicional').prop('required', true);
    } else {
        $('#otroMotivoContainer').hide();
        $('#motivoAdicional').prop('required', false);
    }
});

// Modificar el envío del formulario para incluir el motivo adicional
$('#formCancelarVenta').on('submit', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const formData = new FormData(this);
    
    // Agregar lógica adicional si es necesario antes del envío
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                alert('Venta cancelada correctamente');
                $('#modalCancelarVenta').modal('hide');
                window.location.reload();
            } else {
                alert(response.message || 'Error al cancelar la venta');
            }
        },
        error: function(xhr) {
            alert('Error al procesar la solicitud');
        }
    });
});
    </script>

    <style>
        /* Estilos generales */
        .modal-lg {
            max-width: 900px;
        }
        .label {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
        }
        .box-header {
            padding: 15px;
        }
        .box-title {
            font-size: 18px;
        }
        
        /* Estilos específicos para el modal de agregar venta */
        #modalAgregarVenta #tablaProductos {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        #modalAgregarVenta #tablaProductos th, 
        #modalAgregarVenta #tablaProductos td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        #modalAgregarVenta #tablaProductos th {
            background-color: #e9ecef;
            color: #6c757d;
            font-weight: bold;
        }
        #modalAgregarVenta #tablaProductos input[type="number"] {
            width: 70px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #modalAgregarVenta .table-responsive {
            overflow-x: auto;
        }
        
        /* Ocultar elementos no deseados */
        #modalAgregarVenta .dataTables_length,
        #modalAgregarVenta .dataTables_filter,
        #modalAgregarVenta .dataTables_info,
        #modalAgregarVenta .dataTables_paginate,
        #modalAgregarVenta .dataTables_empty {
            display: none !important;
        }
        
        /* Ocultar mensaje "No hay datos" */
        #tablaProductos tbody:empty {
            display: none;
        }
        
        /* Campos de pago */
        #efectivoFields, #terminalFields, #transferenciaFields {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
@endsection