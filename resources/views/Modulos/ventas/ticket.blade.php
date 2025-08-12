<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Venta #{{ $venta->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
            padding: 5px;
        }
        .ticket {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 16px;
            margin: 5px 0;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        .info-venta {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .info-venta p {
            margin: 3px 0;
        }
        .tabla-productos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .tabla-productos th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding: 3px 0;
        }
        .tabla-productos td {
            padding: 3px 0;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h2>{{ $sucursal->nombre }}</h2>
            <p>{{ $sucursal->direccion }}</p>
            <p>Tel: {{ $sucursal->telefono }}</p>
        </div>
        
        <div class="info-venta">
            <p><strong>Ticket #:</strong> {{ str_pad($venta->id, 8, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Atendido por:</strong> {{ $usuario->name }}</p>
            <p><strong>Método de pago:</strong> {{ ucfirst($venta->metodo_pago) }}</p>
        </div>
        
        <table class="tabla-productos">
            <thead>
                <tr>
                    <th>Cant</th>
                    <th>Descripción</th>
                    <th>P.Unit</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->producto->codigo }} - {{ $detalle->producto->nombre }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total">
            <p>TOTAL: ${{ number_format($venta->total, 2) }}</p>
            @if($venta->metodo_pago == 'efectivo')
                <p>Efectivo: ${{ number_format($venta->efectivo_recibido, 2) }}</p>
                <p>Cambio: ${{ number_format($venta->efectivo_recibido - $venta->total, 2) }}</p>
            @endif
        </div>
        
        <div class="footer">
            <p>¡Gracias por su compra!</p>
            <p>{{ config('app.name') }}</p>
            <p>Folio fiscal: {{ $venta->id }}</p>
        </div>
    </div>
    
<script>
    // Solo intentar imprimir si es la ventana principal
    if(window.opener === null) {
        window.onload = function() {
            setTimeout(function() {
                window.print();
                
                // Opcional: cerrar después de imprimir
                setTimeout(function() {
                    window.close();
                }, 1000);
            }, 500);
        };
    }
</script>
</body>
</html>