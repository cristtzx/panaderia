<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionIngrediente extends Model
{
    protected $table = 'notificaciones_ingredientes';
    protected $fillable = [
        'ingrediente_id',
        'umbral',
        'destinatarios',
        'mensaje_personalizado',
        'activo',
        'ultimo_envio',
        'veces_enviado'
    ];

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }

    public $timestamps = false;



}



