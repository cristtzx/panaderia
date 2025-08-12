@extends('welcome')

@section('contenido')
<div class="content-wrapper">
    <section class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fa fa-utensils text-primary"></i> Buscador de Recetas</h1>
            <a href="{{ route('recetas.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Volver a mis recetas
            </a>
        </div>
    </section>

    <section class="content">
        <div class="card shadow">
            <div class="card-body">
                <!-- Modal para el buscador de recetas -->
                <div class="modal fade" id="modalMealDB">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-utensils"></i> ¿Qué puedo cocinar?
                                </h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Ingredientes principales:</label>
                                            <select id="ingredientes-meals" class="form-control" multiple>
                                                @foreach($userIngredients as $ing)
                                                    <option value="{{ $ing }}">{{ ucfirst($ing) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tu inventario:</label>
                                            <div class="inventario-container p-3 border rounded">
                                                @foreach($userInventory as $item)
                                                    <span class="badge badge-light m-1 inventory-item">{{ $item }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <button id="btn-buscar-meals" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Buscar recetas
                                        </button>
                                    </div>
                                    <div class="col-md-7">
                                        <div id="resultados-meals" class="results-container">
                                            <div class="text-center text-muted py-5">
                                                <i class="fas fa-utensils fa-3x mb-3"></i>
                                                <p>Selecciona ingredientes para ver recetas disponibles</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal del buscador -->
                <div class="text-center mb-4">
                    <h3>Descubre recetas basadas en tus ingredientes</h3>
                    <p class="text-muted">Selecciona los ingredientes que tienes disponibles y encuentra recetas que puedas preparar</p>
                    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalMealDB">
                        <i class="fas fa-search"></i> Abrir Buscador de Recetas
                    </button>
                </div>

                <!-- Sección de recetas recomendadas (se llenará dinámicamente) -->
                <div id="featured-recipes" class="mt-5">
                    <h4 class="mb-3"><i class="fas fa-star"></i> Recetas Destacadas</h4>
                    <div class="row" id="random-recipes-container">
                        <!-- Se llenará con AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- CSS personalizado -->
<style>
    .inventario-container {
        max-height: 200px;
        overflow-y: auto;
        background-color: #f8f9fa;
    }

    .inventory-item {
        cursor: pointer;
        transition: all 0.2s;
    }

    .inventory-item:hover {
        background-color: #e9ecef !important;
    }

    .results-container {
        max-height: 70vh;
        overflow-y: auto;
    }

    .recipe-card {
        transition: transform 0.3s;
        margin-bottom: 20px;
    }

    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .instructions-container {
        max-height: 200px;
        overflow-y: auto;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    .list-group-item-success {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .list-group-item-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .recipe-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>
@endsection

@section('scripts')
<!-- JavaScript necesario -->
<script>
$(document).ready(function() {
    // Cargar algunas recetas aleatorias al inicio
    cargarRecetasAleatorias();

    // Inicializar select2 para ingredientes
    $('#ingredientes-meals').select2({
        placeholder: "Selecciona ingredientes...",
        tags: true,
        tokenSeparators: [',', ' '],
        width: '100%'
    });

    // Buscar recetas
    $('#btn-buscar-meals').click(buscarRecetasPorIngredientes);
});

function cargarRecetasAleatorias() {
    $.get('https://www.themealdb.com/api/json/v1/1/random.php', function(data) {
        const meal = data.meals[0];
        mostrarRecetaDestacada(meal);
    });

    // Podemos hacer varias llamadas para más recetas aleatorias
    for (let i = 0; i < 2; i++) {
        $.get('https://www.themealdb.com/api/json/v1/1/random.php', function(data) {
            const meal = data.meals[0];
            mostrarRecetaDestacada(meal);
        });
    }
}

function mostrarRecetaDestacada(meal) {
    const ingredients = [];
    for (let i = 1; i <= 20; i++) {
        if (meal['strIngredient' + i]) {
            ingredients.push({
                name: meal['strIngredient' + i],
                measure: meal['strMeasure' + i]
            });
        }
    }

    const html = `
        <div class="col-md-4">
            <div class="card recipe-card">
                <img src="${meal.strMealThumb}" class="card-img-top" alt="${meal.strMeal}">
                <div class="card-body">
                    <h5 class="card-title">${meal.strMeal}</h5>
                    <p class="card-text">${meal.strCategory} - ${meal.strArea}</p>
                    <button class="btn btn-sm btn-primary btn-ver-detalle" data-meal-id="${meal.idMeal}">
                        <i class="fas fa-info-circle"></i> Ver detalles
                    </button>
                </div>
            </div>
        </div>
    `;

    $('#random-recipes-container').append(html);
}

function buscarRecetasPorIngredientes() {
    const ingredientes = $('#ingredientes-meals').val();
    const inventario = [];
    
    $('.inventory-item').each(function() {
        inventario.push($(this).text().trim().toLowerCase());
    });

    if (!ingredientes || ingredientes.length === 0) {
        Swal.fire('Ups!', 'Selecciona al menos un ingrediente principal', 'warning');
        return;
    }

    $('#resultados-meals').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Buscando...</span>
            </div>
            <p class="mt-2">Buscando recetas en TheMealDB...</p>
        </div>
    `);

    // Usamos nuestro endpoint de Laravel que hace las consultas a la API
    $.ajax({
        url: '/api/meals/recommend',
        method: 'POST',
        data: {
            ingredients: ingredientes,
            inventory: inventario
        },
        success: function(response) {
            if (response.success && response.data.length > 0) {
                mostrarResultadosMeals(response.data, inventario);
            } else {
                $('#resultados-meals').html(`
                    <div class="alert alert-info">
                        No encontramos recetas que coincidan exactamente. 
                        Intenta con otros ingredientes.
                    </div>
                `);
            }
        },
        error: function() {
            $('#resultados-meals').html(`
                <div class="alert alert-danger">
                    Error al conectar con TheMealDB. Intenta nuevamente.
                </div>
            `);
        }
    });
}

function mostrarResultadosMeals(meals, inventario) {
    let html = '';
    
    meals.forEach(meal => {
        const tieneTodosIngredientes = meal.missing_ingredients.length === 0;
        const ingredientes = [];
        
        // Extraer ingredientes y medidas
        for (let i = 1; i <= 20; i++) {
            if (meal['strIngredient' + i]) {
                ingredientes.push({
                    name: meal['strIngredient' + i],
                    measure: meal['strMeasure' + i],
                    hasIt: inventario.includes(meal['strIngredient' + i].toLowerCase())
                });
            }
        }
        
        html += `
            <div class="card mb-4 ${tieneTodosIngredientes ? 'border-success' : 'border-primary'}">
                <div class="card-header ${tieneTodosIngredientes ? 'bg-success text-white' : 'bg-primary text-white'}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">${meal.strMeal}</h5>
                        <span class="badge ${tieneTodosIngredientes ? 'badge-light' : 'badge-warning'}">
                            ${meal.match_percentage}% coincidencia
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${meal.strMealThumb || 'https://via.placeholder.com/300x200?text=Receta'}" 
                                 class="img-fluid rounded mb-3" 
                                 alt="${meal.strMeal}">
                            ${meal.strCategory ? `<p><strong>Categoría:</strong> ${meal.strCategory}</p>` : ''}
                            ${meal.strArea ? `<p><strong>Cocina:</strong> ${meal.strArea}</p>` : ''}
                        </div>
                        <div class="col-md-8">
                            <h6>Ingredientes:</h6>
                            <ul class="list-group mb-3">
                                ${ingredientes.map(ing => `
                                    <li class="list-group-item ${ing.hasIt ? 'list-group-item-success' : 'list-group-item-warning'}">
                                        ${ing.name} - ${ing.measure}
                                        ${ing.hasIt ? 
                                            '<span class="float-right"><i class="fas fa-check text-success"></i></span>' : 
                                            '<span class="float-right"><i class="fas fa-times text-danger"></i></span>'}
                                    </li>
                                `).join('')}
                            </ul>
                            
                            ${meal.missing_ingredients.length > 0 ? `
                                <div class="alert alert-warning">
                                    <strong><i class="fas fa-exclamation-circle"></i> Te faltan:</strong> 
                                    ${meal.missing_ingredients.join(', ')}
                                </div>
                            ` : `
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> ¡Tienes todos los ingredientes!
                                </div>
                            `}
                            
                            <h6 class="mt-3">Instrucciones:</h6>
                            <div class="instructions-container">
                                ${meal.strInstructions.split('\n').filter(p => p.trim() !== '').map(p => `<p>${p}</p>`).join('')}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    ${meal.strYoutube ? `
                        <a href="${meal.strYoutube}" target="_blank" class="btn btn-danger">
                            <i class="fab fa-youtube"></i> Ver en YouTube
                        </a>
                    ` : ''}
                    <button class="btn btn-outline-primary btn-guardar-receta float-right" 
                            data-meal-id="${meal.idMeal}">
                        <i class="far fa-save"></i> Guardar receta
                    </button>
                </div>
            </div>
        `;
    });
    
    $('#resultados-meals').html(html || `
        <div class="alert alert-info">
            No encontramos recetas con esos ingredientes. Intenta con una combinación diferente.
        </div>
    `);
}

// Evento para ver detalles de recetas destacadas
$(document).on('click', '.btn-ver-detalle', function() {
    const mealId = $(this).data('meal-id');
    
    $.get(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${mealId}`, function(response) {
        const meal = response.meals[0];
        const inventario = [];
        
        $('.inventory-item').each(function() {
            inventario.push($(this).text().trim().toLowerCase());
        });
        
        $('#resultados-meals').html('');
        mostrarResultadosMeals([meal], inventario);
        $('#modalMealDB').modal('show');
    });
});
</script>
@endsection