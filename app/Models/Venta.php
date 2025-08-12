<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'sucursales_id',
        'user_id',
        'total',
        'metodo_pago',
        'efectivo_recibido',
        'cambio',
        'estado',
        'motivo_cancelacion_id',
    ];

        public function sucursal()
    {
     return $this->belongsTo(Sucursal::class, 'sucursales_id');
    }


    public function user()
    {
     return $this->belongsTo(user::class, 'user_id');
    }


    public function motivo_cancelacion()
    {
     return $this->belongsTo(MotivoCancelacion::class, 'motivo_cancelacion_id');
    }








}




