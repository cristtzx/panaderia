<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ingrediente;
use Illuminate\Support\Facades\Log;

class MealDBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }






    public function recommend(Request $request)
    {
        try {
            $request->validate([
                'ingredients' => 'required|array|min:1',
                'ingredients.*' => 'string|max:100',
                'inventory' => 'required|array',
                'inventory.*' => 'string|max:100'
            ]);

            $ingredients = array_map('strtolower', $request->ingredients);
            $inventory = array_map('strtolower', $request->inventory);
            
            $results = $this->searchMealsByIngredients($ingredients);
            
            if (empty($results)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron recetas con esos ingredientes'
                ], 404);
            }

            $detailedResults = $this->processMealsDetails($results, $inventory);
            
            return response()->json([
                'success' => true,
                'data' => $detailedResults
            ]);

        } catch (\Exception $e) {
            Log::error('Error en MealDBController: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar la solicitud',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    protected function searchMealsByIngredients(array $ingredients)
    {
        $results = [];
        
        foreach ($ingredients as $ingredient) {
            $response = Http::timeout(10)->get('https://www.themealdb.com/api/json/v1/1/filter.php', [
                'i' => urlencode($ingredient)
            ]);
            
            if (!$response->successful()) {
                Log::warning("Fallo al buscar ingrediente: $ingredient. Código: " . $response->status());
                continue;
            }
            
            $data = $response->json();
            
            if (empty($data['meals'])) {
                continue;
            }
            
            foreach ($data['meals'] as $meal) {
                $mealId = $meal['idMeal'];
                if (!isset($results[$mealId])) {
                    $results[$mealId] = $meal;
                    $results[$mealId]['match_score'] = 0;
                    $results[$mealId]['matched_ingredients'] = [];
                }
                $results[$mealId]['match_score']++;
                $results[$mealId]['matched_ingredients'][] = $ingredient;
            }
        }
        
        return $results;
    }

    protected function processMealsDetails(array $meals, array $inventory)
    {
        $detailedResults = [];
        
        foreach ($meals as $meal) {
            $details = $this->getMealDetails($meal['idMeal']);
            
            if (!$details) {
                continue;
            }
            
            $processedMeal = $this->processSingleMeal($details, $inventory);
            $processedMeal['matched_ingredients'] = $meal['matched_ingredients'];
            $detailedResults[] = $processedMeal;
        }
        
        usort($detailedResults, function($a, $b) {
            return $b['match_percentage'] <=> $a['match_percentage'];
        });
        
        return $detailedResults;
    }

    protected function processSingleMeal(array $meal, array $inventory)
    {
        $ingredientsList = $this->extractIngredientsWithMeasures($meal);
        $missingIngredients = [];
        $matchingIngredients = [];
        
        foreach ($ingredientsList as $ingredient) {
            if (!in_array(strtolower($ingredient['name']), $inventory)) {
                $missingIngredients[] = $ingredient['name'];
            } else {
                $matchingIngredients[] = $ingredient['name'];
            }
        }
        
        $totalIngredients = count($ingredientsList);
        $matchingCount = count($matchingIngredients);
        
        return [
            'id' => $meal['idMeal'],
            'name' => $meal['strMeal'],
            'category' => $meal['strCategory'],
            'area' => $meal['strArea'],
            'instructions' => $meal['strInstructions'],
            'image' => $meal['strMealThumb'],
            'youtube' => $meal['strYoutube'],
            'ingredients' => $ingredientsList,
            'missing_ingredients' => $missingIngredients,
            'matching_ingredients' => $matchingIngredients,
            'match_percentage' => $totalIngredients > 0 ? round(($matchingCount / $totalIngredients) * 100, 2) : 0,
            'total_ingredients' => $totalIngredients
        ];
    }

    protected function getMealDetails($mealId)
    {
        $response = Http::timeout(10)->get('https://www.themealdb.com/api/json/v1/1/lookup.php', [
            'i' => $mealId
        ]);
        
        if (!$response->successful()) {
            Log::error("Fallo al obtener detalles de comida ID: $mealId");
            return null;
        }
        
        $data = $response->json();
        
        return $data['meals'][0] ?? null;
    }

    protected function extractIngredientsWithMeasures(array $meal)
    {
        $ingredients = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $ingredient = trim($meal['strIngredient' . $i] ?? '');
            $measure = trim($meal['strMeasure' . $i] ?? '');
            
            if (!empty($ingredient)) {
                $ingredients[] = [
                    'name' => ucfirst($ingredient),
                    'measure' => $measure,
                    'original_name' => $ingredient
                ];
            }
        }
        
        return $ingredients;
    }
}