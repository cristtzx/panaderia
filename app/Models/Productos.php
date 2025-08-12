<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
     protected $table = 'productos';
    
     protected $fillable = [
        'id_categoria',
        'id_recetas',
        'nombre',
        'codigo',
        'stock',
        'precio_estimado',
        'precio_venta',
        'imagen',
        'maneja_lotes',
        'dias_caducidad',
        'agregado'
    ];




    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id');
    }



    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_recetas', 'id');
    }


      public $timestamps = false;




    public function lotes()
    {
     return $this->hasMany(Lote::class)->orderBy('fecha_caducidad');
    }
    
}
