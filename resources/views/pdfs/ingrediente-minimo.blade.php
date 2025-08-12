<!DOCTYPE html>
<html>
<head>
    <title>Ingredientes con Stock Mínimo</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .title { text-align: center; margin-bottom: 20px; }
        .warning { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">
        <h1 class="warning">Ingredientes bajo stock mínimo</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Diferencia</th>
                <th>Unidad de Medida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingredientes as $ingrediente)
            <tr>
                <td>{{ $ingrediente->Nombre }}</td>
                <td>{{ $ingrediente->Stock }}</td>
                <td>{{ $ingrediente->Stock_minimo }}</td>
                <td class="warning">{{ $ingrediente->Stock_minimo - $ingrediente->Stock }}</td>
                <td>{{ $ingrediente->medida->nombre ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>