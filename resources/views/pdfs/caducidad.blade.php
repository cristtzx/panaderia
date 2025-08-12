<!DOCTYPE html>
<html>
<head>
    <title>Productos por Caducar - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #ffc107; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #ffc107; color: #000; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        .text-success { color: #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Productos por Caducar (Próximos 7 días)</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Lote</th>
                <th>Cantidad</th>
                <th>Caducidad</th>
                <th>Días Restantes</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                @foreach($producto->lotes as $lote)
                    @php
                        $dias = now()->diffInDays($lote->fecha_caducidad, false);
                    @endphp
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $lote->codigo_lote }}</td>
                        <td>{{ $lote->cantidad }}</td>
                        <td>{{ $lote->fecha_caducidad->format('d/m/Y') }}</td>
                        <td>{{ $dias > 0 ? $dias : 'Caducado' }}</td>
                        <td>
                            @if($dias <= 0)
                                <span class="text-danger">CADUCADO</span>
                            @elseif($dias <= 7)
                                <span class="text-warning">PRÓXIMO A CADUCAR</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>