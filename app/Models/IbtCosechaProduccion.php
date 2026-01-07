<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbtCosechaProduccion extends Model
{
    protected $table = 'ibt_cosecha_produccion';

    protected $fillable = [
        'evaluacion_ibt_id',
        'criterio_ciclo_cosecha',
        'recoleccion_fruto',
        'calidad_fruto_cosechado',
        'produccion',
        'puntaje_total',
    ];

    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionIbt::class, 'evaluacion_ibt_id');
    }
}
