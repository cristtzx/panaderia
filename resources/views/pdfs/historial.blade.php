@php
use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Historial de Planificación</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #333; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Historial de Planificación Semanal</h1>
    
    <table>
        <thead>
            <tr>
                <th>Semana</th>
                <th>Periodo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semanas as $semana)
                @php
                    $inicio = Carbon::now()
                        ->setISODate($semana->año, $semana->semana)
                        ->startOfWeek();
                    $fin = $inicio->copy()->endOfWeek();
                @endphp
                <tr>
                    <td>Semana {{ $semana->semana }} - {{ $semana->año }}</td>
                    <td>{{ $inicio->format('d/m/Y') }} al {{ $fin->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>