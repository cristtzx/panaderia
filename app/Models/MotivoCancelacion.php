<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoCancelacion extends Model
{
    protected $table = 'motivo_cancelacion';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
