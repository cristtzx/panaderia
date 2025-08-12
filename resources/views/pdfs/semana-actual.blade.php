<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Plan Semanal</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .dia { background-color: #e6f7ff; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Plan Semanal de Producción</h2>
        <p>Del {{ $inicio->format('d/m/Y') }} al {{ $fin->format('d/m/Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="25%">Día</th>
                <th width="50%">Receta</th>
                <th width="25%">Unidades</th>
            </tr>
        </thead>
        <tbody>
            @php $current = $inicio->copy(); @endphp
            @while($current <= $fin)
                @php
                    $diaPlanes = $planes[$current->format('Y-m-d')] ?? [];
                @endphp
                <tr class="dia">
                    <td>{{ $current->isoFormat('dddd D/M') }}</td>
                    <td colspan="2">
                        @if(count($diaPlanes) > 0)
                            {{ count($diaPlanes) }} receta(s) programada(s)
                        @else
                            Sin programación
                        @endif
                    </td>
                </tr>
                @foreach($diaPlanes as $plan)
                <tr>
                    <td></td>
                    <td>{{ $plan->receta->nombre }}</td>
                    <td>{{ $plan->unidades_esperadas }}</td>
                </tr>
                @endforeach
                @php $current->addDay(); @endphp
            @endwhile
        </tbody>
    </table>
</body>
</html>