<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
   

    protected $table = 'medidas';
    protected $fillable = [
        'nombre',
    ];
    
    public function ingredientes()
    {
        return $this->hasMany(Ingrediente::class, 'id_medidas');
    }


    public $timestamps = false;
}
