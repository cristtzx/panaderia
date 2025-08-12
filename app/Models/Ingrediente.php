<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $table = 'ingredientes';

    protected $fillable = [
        'Nombre',
        'Stock',
        'Stock_minimo',
        'Stock_maximo',
        'id_medidas',

    ];

    public function notificacion()
    {
       return $this->hasOne(NotificacionIngrediente::class);
    }

    public function medida()
    {
     return $this->belongsTo(Medida::class, 'id_medidas');
    }

    public function recetas()
    {
        return $this->belongsToMany(Receta::class)
            ->withPivot('cantidad', 'unidad')
            ->withTimestamps();
    }




    public $timestamps = false; 

}
