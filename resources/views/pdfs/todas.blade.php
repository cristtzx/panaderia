<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Todas las Recetas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap');
        
        body { 
            font-family: 'Roboto', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f9f9f9;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e67e22;
        }
        
        .title { 
            font-family: 'Playfair Display', serif;
            font-size: 32px; 
            color: #2c3e50;
            margin-bottom: 5px;
            font-weight: 700;
        }
        
        .subtitle {
            font-size: 16px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .page {
            position: relative;
            min-height: 100vh;
            padding: 40px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .receta { 
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        
        .receta-title { 
            font-family: 'Playfair Display', serif;
            font-size: 24px; 
            color: #e67e22;
            border-bottom: 2px dashed #e67e22;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .receta-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .receta-meta span {
            background: #f1f1f1;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .receta-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 10px;
            border-left: 4px solid #e67e22;
            padding-left: 10px;
        }
        
        .ingredientes-list { 
            list-style-type: none; 
            padding-left: 0;
            column-count: 2;
            column-gap: 30px;
        }
        
        .ingredientes-list li { 
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
            break-inside: avoid;
        }
        
        .ingredientes-list li:before {
            content: "•";
            color: #e67e22;
            font-size: 20px;
            position: absolute;
            left: 0;
            top: -2px;
        }
        
        .instrucciones {
            white-space: pre-line;
            padding-left: 10px;
        }
        
        .instrucciones p {
            margin-bottom: 15px;
        }
        
        .footer { 
            text-align: center; 
            font-size: 12px; 
            color: #7f8c8d; 
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .page-number {
            position: absolute;
            bottom: 20px;
            right: 40px;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        @media print {
            body {
                background-color: transparent;
            }
            .page {
                box-shadow: none;
                margin-bottom: 0;
                page-break-after: always;
            }
            .page:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    @foreach($recetas as $receta)
    <div class="page">
        @if($loop->first)
        <div class="header">
            <div class="title">Libro de Recetas</div>
            <div class="subtitle">Generado el {{ now()->format('d/m/Y H:i') }}</div>
        </div>
        @endif
        
        <div class="receta">
            <div class="receta-title">{{ $receta->nombre }}</div>
            
            <div class="receta-meta">
                <span>⏱️ Tiempo: {{ $receta->tiempo_preparacion }} min</span>
                @if($receta->porciones)
                <span>🍽️ Porciones: {{ $receta->porciones }}</span>
                @endif
                @if($receta->dificultad)
                <span>⚡ Dificultad: {{ $receta->dificultad }}</span>
                @endif
            </div>
            
            @if($receta->descripcion)
            <div class="receta-section">
                <div class="section-title">Descripción</div>
                <p>{{ $receta->descripcion }}</p>
            </div>
            @endif
            
            <div class="receta-section">
                <div class="section-title">Ingredientes</div>
                <ul class="ingredientes-list">
                    @foreach($receta->ingredientes as $ingrediente)
                        <li>{{ $ingrediente->Nombre }} - {{ $ingrediente->pivot->cantidad }} {{ $ingrediente->pivot->unidad }}</li>
                    @endforeach
                </ul>
            </div>
            
            <div class="receta-section">
                <div class="section-title">Instrucciones</div>
                <div class="instrucciones">{{ $receta->instrucciones }}</div>
            </div>
            
            <div class="footer">
                Receta {{ $loop->iteration }} de {{ $loop->count }} | Sistema de Gestión de Recetas
            </div>
            
            <div class="page-number">Página {{ $loop->iteration }}</div>
        </div>
    </div>
    @endforeach
</body>
</html>