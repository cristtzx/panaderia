<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receta: {{ $receta->nombre }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .title { color: #2c3e50; font-size: 24px; margin-bottom: 5px; }
        .subtitle { color: #7f8c8d; font-size: 16px; }
        .section { margin-bottom: 15px; }
        .section-title { background-color: #f8f9fa; padding: 5px 10px; font-weight: bold; }
        .ingredientes-list { list-style-type: none; padding-left: 0; }
        .ingredientes-list li { margin-bottom: 5px; }
        .instrucciones { white-space: pre-line; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #7f8c8d; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $receta->nombre }}</div>
        <div class="subtitle">Tiempo de preparación: {{ $receta->tiempo_preparacion }} minutos</div>
    </div>

    <div class="section">
        <div class="section-title">Descripción</div>
        <div>{{ $receta->descripcion }}</div>
    </div>

    <div class="section">
        <div class="section-title">Ingredientes</div>
        <ul class="ingredientes-list">
            @foreach($receta->ingredientes as $ingrediente)
                <li>{{ $ingrediente->Nombre }} - {{ $ingrediente->pivot->cantidad }} {{ $ingrediente->pivot->unidad }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <div class="section-title">Instrucciones</div>
        <div class="instrucciones">{{ $receta->instrucciones }}</div>
    </div>

    <div class="footer">
        Receta generada el {{ now()->format('d/m/Y') }} - Sistema de Gestión de Recetas
    </div>
</body>
</html>