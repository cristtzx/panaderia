<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WeeklyPlan extends Model {
    protected $fillable = [
        'recetas_id', 'day', 'unidades_esperadas', 
        'unidades_reales', 'ajustes', 'notas'
    ];

    protected $appends = ['day_name']; // Agrega este atributo calculado

    // Accesor para obtener el nombre del día
    protected function dayName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                return $days[date('w', strtotime($this->day))];
            }
        );
    }

    // Relación: Un plan pertenece a una receta
    public function receta() {
        return $this->belongsTo(Receta::class, 'recetas_id');
    }

}

