<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredienteReceta extends Model
{
    protected $table = 'ingrediente_receta';
    
    protected $fillable = [
        'id_recetas',
        'id_ingredientes',
        'cantidad',
        'unidad' 
    ];

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }


    public $timestamps = false;
    
}