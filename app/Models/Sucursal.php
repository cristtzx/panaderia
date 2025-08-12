<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'latitud',
        'longitud',
        'telefono',
        'correo',
        'encargado',
        'estado',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_sucursal');
    }




    public $timestamps = false; 
}
