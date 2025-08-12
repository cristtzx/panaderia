@extends('welcome')

@section('contenido')
<div class="content-wrapper" style="background-color: #f8f9fa;">
    <!-- Encabezado con gradiente -->
    <section class="content-header" style="background: linear-gradient(135deg, #3c8dbc 0%, #367fa9 100%); padding: 20px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h1 style="color: white; margin: 0; font-weight: 300; font-size: 28px;">
            <i class="fa fa-tachometer-alt mr-2"></i> Bienvenido, {{ Auth::user()->name ?? 'Usuario' }}
        </h1>
        <small style="color: rgba(255,255,255,0.8); font-size: 14px;">Panel de control - Resumen completo de operaciones</small>
    </section>

    <section class="content" style="padding: 20px;">
        <!-- Alertas rápidas con animación -->
        @if(!empty($alertas))
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="box box-solid animated fadeIn" style="border-left: 3px solid #f39c12; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header with-border" style="background-color: #fefefe; border-bottom: 1px solid #eee;">
                        <h3 class="box-title" style="color: #555; font-weight: 600;">
                            <i class="fa fa-exclamation-circle mr-2 text-warning"></i> Alertas importantes
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 15px;">
                        <div class="alert-container">
                            @foreach($alertas as $a)
                                <div class="alert alert-{{ $a['sev'] }} alert-dismissible" style="border-radius: 4px; margin-bottom: 10px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="margin: 0;">
                                        <i class="icon fa fa-{{ $a['tipo'] === 'sin_stock' ? 'times-circle' : ($a['tipo'] === 'stock_critico' ? 'exclamation-triangle' : 'clock') }}"></i> 
                                        {{ ucfirst(str_replace('_', ' ', $a['tipo'])) }}
                                    </h5>
                                    {{ $a['msg'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tarjetas Resumen con hover -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-gradient-success" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner">
                        <h3 style="font-weight: 700;">{{ $totalVentas ?? 0 }}</h3>
                        <p style="font-size: 16px;">Ventas Totales</p>
                    </div>
                    <div class="icon" style="font-size: 70px;">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="/ventas" class="small-box-footer" style="background: rgba(0,0,0,0.1);">
                        Más info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-gradient-info" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner">
                        <h3 style="font-weight: 700;">${{ number_format($gananciasTotales ?? 0, 2) }}</h3>
                        <p style="font-size: 16px;">Ganancias Totales</p>
                    </div>
                    <div class="icon" style="font-size: 70px;">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="/ventas" class="small-box-footer" style="background: rgba(0,0,0,0.1);">
                        Más info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-gradient-danger" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner">
                        <h3 style="font-weight: 700;">{{ $inventarioBajo->count() ?? 0 }}</h3>
                        <p style="font-size: 16px;">Productos con stock bajo</p>
                    </div>
                    <div class="icon" style="font-size: 70px;">
                        <i class="ion ion-alert-circled"></i>
                    </div>
                    <a href="/Productos" class="small-box-footer" style="background: rgba(0,0,0,0.1);">
                        Más info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Fila: Gráficos principales -->
        <div class="row mb-4">
            <!-- Ventas por Mes -->
            <div class="col-md-6">
                <div class="box box-primary" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header" style="border-bottom: 1px solid #eee; background-color: #fefefe; border-radius: 8px 8px 0 0;">
                        <h3 class="box-title" style="color: #3c8dbc; font-weight: 600;">
                            <i class="fa fa-chart-line mr-2"></i> Ventas por Mes
                        </h3>
                        <div class="box-tools pull-right">
                            <select class="form-control input-sm" style="height: 30px; padding: 0 10px; border-radius: 4px; border: 1px solid #ddd;">
                                <option>2023</option>
                                <option selected>2024</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 15px;">
                        <canvas id="ventasChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Métodos de pago -->
            <div class="col-md-6">
                <div class="box box-warning" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header" style="border-bottom: 1px solid #eee; background-color: #fefefe; border-radius: 8px 8px 0 0;">
                        <h3 class="box-title" style="color: #f39c12; font-weight: 600;">
                            <i class="fa fa-credit-card mr-2"></i> Métodos de pago (mes actual)
                        </h3>
                    </div>
                    <div class="box-body" style="padding: 15px; position: relative;">
                        <canvas id="metodosPagoChart" height="200"></canvas>
                        @if(($metodosPagoLabels ?? collect())->isEmpty())
                            <div class="text-center text-muted" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <i class="fa fa-database fa-3x mb-2"></i>
                                <p>No hay datos disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila: Más gráficos -->
        <div class="row mb-4">
            <!-- Top productos -->
            <div class="col-md-6">
                <div class="box box-success" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header" style="border-bottom: 1px solid #eee; background-color: #fefefe; border-radius: 8px 8px 0 0;">
                        <h3 class="box-title" style="color: #00a65a; font-weight: 600;">
                            <i class="fa fa-cubes mr-2"></i> Top 5 productos (mes actual)
                        </h3>
                    </div>
                    <div class="box-body" style="padding: 15px; position: relative;">
                        <canvas id="topProductosChart" height="200"></canvas>
                        @if(($topProductosLabels ?? collect())->isEmpty())
                            <div class="text-center text-muted" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <i class="fa fa-database fa-3x mb-2"></i>
                                <p>No hay datos disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ventas por sucursal -->
            <div class="col-md-6">
                <div class="box box-info" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header" style="border-bottom: 1px solid #eee; background-color: #fefefe; border-radius: 8px 8px 0 0;">
                        <h3 class="box-title" style="color: #00c0ef; font-weight: 600;">
                            <i class="fa fa-store mr-2"></i> Ventas por sucursal (mes actual)
                        </h3>
                    </div>
                    <div class="box-body" style="padding: 15px; position: relative;">
                        <canvas id="ventasSucursalChart" height="200"></canvas>
                        @if(($sucLabels ?? collect())->isEmpty())
                            <div class="text-center text-muted" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <i class="fa fa-database fa-3x mb-2"></i>
                                <p>No hay datos disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila: Inventarios y caducidad -->
        <div class="row mb-4">
            <!-- Productos por caducar -->
            <div class="col-md-6">
                <div class="box box-danger" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header" style="border-bottom: 1px solid #eee; background-color: #fefefe; border-radius: 8px 8px 0 0;">
                        <h3 class="box-title" style="color: #dd4b39; font-weight: 600;">
                            <i class="fa fa-clock mr-2"></i> Productos por caducar (≤ {{ $caducaEnDias ?? 7 }} días)
                        </h3>
                        <div class="box-tools pull-right">
                            <span class="badge bg-red">{{ $productosPorCaducar->count() }}</span>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 0; max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover table-striped">
                            <thead style="background-color: #f5f5f5; position: sticky; top: 0;">
                                <tr>
                                    <th style="width: 10%;">Lote ID</th>
                                    <th style="width: 40%;">Producto</th>
                                    <th style="width: 15%;">Cantidad</th>
                                    <th style="width: 20%;">Caduca</th>
                                    <th style="width: 15%;" class="text-center">Días</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productosPorCaducar as $p)
                                    <tr class="{{ ($p->dias_restantes ?? 0) <= 2 ? 'bg-danger-light' : '' }}">
                                        <td>#{{ $p->lote_id }}</td>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-name">{{ $p->nombre }}</div>
                                                <small class="text-muted">Lote entrada: {{ \Carbon\Carbon::parse($p->fecha_entrada)->format('d/m/Y') }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $p->cantidad }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->fecha_caducidad)->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ ($p->dias_restantes ?? 0) <= 2 ? 'bg-red' : 'bg-orange' }}">
                                                {{ $p->dias_restantes }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fa fa-check-circle fa-2x mb-2 text-success"></i>
                                            <p>No hay productos próximos a caducar</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-center" style="background-color: #fefefe; border-radius: 0 0 8px 8px;">
                        <a href="/Productos" class="btn btn-sm btn-flat btn-danger">Ver todos</a>
                    </div>
                </div>
            </div>

            <!-- Inventario bajo -->
            <div class="col-md-6">
                <div class="box box-danger" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header" style="border-bottom: 1px solid #eee; background-color: #fefefe; border-radius: 8px 8px 0 0;">
                        <h3 class="box-title" style="color: #dd4b39; font-weight: 600;">
                            <i class="fa fa-exclamation-triangle mr-2"></i> Inventario bajo (≤ {{ $umbral ?? 10 }} unidades)
                        </h3>
                        <div class="box-tools pull-right">
                            <span class="badge bg-red">{{ $inventarioBajo->count() }}</span>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 0; max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover table-striped">
                            <thead style="background-color: #f5f5f5; position: sticky; top: 0;">
                                <tr>
                                    <th style="width: 10%;">ID</th>
                                    <th style="width: 50%;">Producto</th>
                                    <th style="width: 20%;">Stock</th>
                                    <th style="width: 20%;" class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventarioBajo as $p)
                                    <tr class="{{ $p->stock <= $critico ? 'bg-danger-light' : '' }}">
                                        <td>{{ $p->id }}</td>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-name">{{ $p->nombre }}</div>
                                                <small class="text-muted">Código: {{ $p->codigo ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $p->stock }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $p->stock <= $critico ? 'bg-red' : 'bg-orange' }}">
                                                {{ $p->stock <= $critico ? 'Crítico' : 'Bajo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fa fa-check-circle fa-2x mb-2 text-success"></i>
                                            <p>Todo en orden. No hay inventario bajo.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-center" style="background-color: #fefefe; border-radius: 0 0 8px 8px;">
                        <a href="/Productos" class="btn btn-sm btn-flat btn-danger">Ver todos</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila: Últimas ventas -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="box-header" style="border-bottom: 1px solid #eee; background-color: #fefefe; border-radius: 8px 8px 0 0;">
                        <h3 class="box-title" style="color: #00c0ef; font-weight: 600;">
                            <i class="fa fa-history mr-2"></i> Últimas 5 Ventas
                        </h3>
                        <div class="box-tools pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-filter"></i> Filtros
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <a class="dropdown-item" href="#">Hoy</a>
                                    <a class="dropdown-item" href="#">Esta semana</a>
                                    <a class="dropdown-item" href="#">Este mes</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Ver todas</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 0; max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-striped">
                            <thead style="background-color: #f5f5f5; position: sticky; top: 0;">
                                <tr>
                                    <th style="width: 8%;">ID</th>
                                    <th style="width: 25%;">Cliente</th>
                                    <th style="width: 20%;">Sucursal</th>
                                    <th style="width: 15%;">Total</th>
                                    <th style="width: 15%;">Método Pago</th>
                                    <th style="width: 17%;">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimasVentas as $venta)
                                <tr>
                                    <td>#{{ $venta->id }}</td>
                                    <td>{{ $venta->cliente->nombre ?? 'Consumidor final' }}</td>
                                    <td>{{ $venta->sucursal->nombre ?? 'N/A' }}</td>
                                    <td class="text-success" style="font-weight: 600;">${{ number_format($venta->total, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $venta->metodo_pago === 'efectivo' ? 'bg-green' : 
                                               ($venta->metodo_pago === 'tarjeta' ? 'bg-blue' : 'bg-purple') }}">
                                            {{ ucfirst($venta->metodo_pago) }}
                                        </span>
                                    </td>
                                    <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fa fa-database fa-2x mb-2"></i>
                                        <p>No hay ventas registradas</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix" style="background-color: #fefefe; border-radius: 0 0 8px 8px;">
                        <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-info btn-flat pull-right">Ver todas las ventas</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Scripts de gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Colores personalizados
    const colors = {
        primary: '#3c8dbc',
        success: '#00a65a',
        info: '#00c0ef',
        warning: '#f39c12',
        danger: '#dd4b39',
        purple: '#605ca8',
        teal: '#39cccc'
    };

    /* Ventas por Mes */
    if (document.getElementById('ventasChart')) {
        const ctx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                datasets: [{
                    label: 'Ventas ($)',
                    data: @json(array_values($ventasFormateadas ?? array_fill(1,12,0))),
                    backgroundColor: 'rgba(60, 141, 188, 0.7)',
                    borderColor: 'rgba(60, 141, 188, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(60, 141, 188, 0.9)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    /* Top productos */
    if (document.getElementById('topProductosChart')) {
        const ctx = document.getElementById('topProductosChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($topProductosLabels ?? []),
                datasets: [{
                    label: 'Cantidad vendida',
                    data: @json($topProductosData ?? []),
                    backgroundColor: 'rgba(0, 166, 90, 0.7)',
                    borderColor: 'rgba(0, 166, 90, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(0, 166, 90, 0.9)'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: { 
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    /* Métodos de pago */
    if (document.getElementById('metodosPagoChart')) {
        const ctx = document.getElementById('metodosPagoChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($metodosPagoLabels ?? []),
                datasets: [{
                    data: @json($metodosPagoData ?? []),
                    backgroundColor: [
                        colors.primary,
                        colors.success,
                        colors.warning,
                        colors.danger,
                        colors.purple,
                        colors.teal
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    /* Ventas por sucursal */
    if (document.getElementById('ventasSucursalChart')) {
        const ctx = document.getElementById('ventasSucursalChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($sucLabels ?? []),
                datasets: [{
                    label: 'Ventas ($)',
                    data: @json($sucData ?? []),
                    backgroundColor: 'rgba(0, 192, 239, 0.7)',
                    borderColor: 'rgba(0, 192, 239, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(0, 192, 239, 0.9)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Efecto hover en las tarjetas
    document.querySelectorAll('.small-box').forEach(box => {
        box.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        box.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
});
</script>

<style>
    /* Estilos personalizados */
    .bg-gradient-success {
        background: linear-gradient(135deg, #00a65a 0%, #008d4c 100%) !important;
        color: white !important;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #00c0ef 0%, #00a7d0 100%) !important;
        color: white !important;
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e08e0b 100%) !important;
        color: white !important;
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #dd4b39 0%, #c53727 100%) !important;
        color: white !important;
    }
    .bg-danger-light {
        background-color: #fdecea !important;
    }
    .product-info {
        display: flex;
        flex-direction: column;
    }
    .product-name {
        font-weight: 600;
        margin-bottom: 2px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
    .alert-container .alert {
        margin-bottom: 10px;
        padding: 10px 15px;
        border-left: 4px solid;
    }
    .badge {
        font-weight: 500;
        padding: 4px 8px;
        font-size: 12px;
    }
    .box-title i {
        margin-right: 8px;
    }
</style>
@endsection