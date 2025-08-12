<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Ingredientes</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .title { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="title">
        <h1>Reporte Completo de Ingredientes</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Stock Máximo</th>
                <th>Unidad de Medida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingredientes as $ingrediente)
            <tr>
                <td>{{ $ingrediente->Nombre }}</td>
                <td>{{ $ingrediente->Stock }}</td>
                <td>{{ $ingrediente->Stock_minimo }}</td>
                <td>{{ $ingrediente->Stock_maximo }}</td>
                <td>{{ $ingrediente->medida->nombre ?? 'N/A' }}</td>
                <td>
                    @if($ingrediente->Stock < $ingrediente->Stock_minimo)
                        <span style="color: red;">Bajo stock</span>
                    @elseif($ingrediente->Stock > $ingrediente->Stock_maximo)
                        <span style="color: orange;">Sobre stock</span>
                    @else
                        <span style="color: green;">Normal</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>