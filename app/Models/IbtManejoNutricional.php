<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbtManejoNutricional extends Model
{
    protected $table = 'ibt_manejo_nutricional';

    protected $fillable = [
        'evaluacion_ibt_id',
        'toma_muestra_foliares',
        'toma_muestras_suelos',
        'censo_produccion',
        'eficacia_fertilizacion',
        'fraccionamiento_fertilizacion',
        'epoca_fertilizacion',
        'medicion_crecimiento',
        'puntaje_total',
    ];

    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionIbt::class, 'evaluacion_ibt_id');
    }
}
