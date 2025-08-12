<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lote extends Model
{
    protected $table = 'lotes';

    protected $fillable = [
    'productos_id',
    'cantidad',
    'fecha_entrada',
    'fecha_caducidad'
    ];

    public function producto()
    {
     return $this->belongsTo(Productos::class);
    }

     public $timestamps = false;

    protected $casts = [
    'fecha_entrada' => 'datetime',
    'fecha_caducidad' => 'datetime',
];


        public function getFechaEntradaFormateadaAttribute()
    {
        return $this->fecha_entrada->format('d/m/Y');
    }

    public function getFechaCaducidadFormateadaAttribute()
    {
        return $this->fecha_caducidad->format('d/m/Y');
    }

    public function getEstaCaducadoAttribute()
    {
        return $this->fecha_caducidad->isPast();
}


}
