<!DOCTYPE html>
<html>
<head>
    <title>Historial de Lotes - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #dc3545; color: white; }
        .text-muted { color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historial de Lotes</h1>
        <p><strong>Generado por:</strong> {{ auth()->user()->name }} | {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Lote</th>
                <th>Cantidad</th>
                <th>Fecha Entrada</th>
                <th>Fecha Caducidad</th>
                <th>Registrado por</th>
                <th>Última Modificación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lotes as $lote)
            <tr>
                <td>{{ $lote->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $lote->codigo_lote }}</td>
                <td>{{ $lote->cantidad }}</td>
                <td>{{ $lote->fecha_entrada->format('d/m/Y') }}</td>
                <td>{{ $lote->fecha_caducidad->format('d/m/Y') }}</td>
                <td>{{ $lote->registrado_por ?? 'Sistema' }}</td>
                <td>
                    @if($lote->updated_at != $lote->created_at)
                        {{ $lote->updated_at->format('d/m/Y H:i') }}<br>
                        @if($lote->modificado_por)
                            <small class="text-muted">por: {{ $lote->modificado_por }}</small>
                        @endif
                    @else
                        Sin modificaciones
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>