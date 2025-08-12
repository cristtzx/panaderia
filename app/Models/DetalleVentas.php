<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVentas extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ]; 

    public $timestamps = false;

    public function venta()
    {
     return $this->belongsTo(venta::class, 'venta_id');
    }

    public function producto()
    {
     return $this->belongsTo(Productos::class, 'producto_id');
    }


}
