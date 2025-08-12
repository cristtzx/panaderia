<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'instrucciones',
        'tiempo_preparacion',

    ];



    public function ingredientes()
    {
     return $this->belongsToMany(Ingrediente::class, 'ingrediente_receta', 'id_recetas', 'id_ingredientes')
                ->withPivot('cantidad', 'unidad');
    }


    public $timestamps = false;


    public function planesSemanales() {
    return $this->hasMany(WeeklyPlan::class, 'recetas_id');
    }

}
