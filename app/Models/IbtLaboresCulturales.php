<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbtLaboresCulturales extends Model
{
    protected $table = 'ibt_labores_culturales';

    protected $fillable = [
        'evaluacion_ibt_id',
        'limpieza_platos',
        'limpieza_interlineas',
        'poda',
        'polinizacion',
        'disposicion_hojas_podadas',
        'mantenimiento_infraestructura',
        'puntaje_total'
    ];

    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionIbt::class, 'evaluacion_ibt_id');
    }
}
