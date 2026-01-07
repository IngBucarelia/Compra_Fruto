<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbtManejoSanitario extends Model
{
    protected $table = 'ibt_manejo_sanitario';

    protected $fillable = [
        'evaluacion_ibt_id',
        'censo_enfermedades_plagas',
        'oportunidad_control',
        'calidad_follaje',
        'area_foliar',
        'censo_palmas_anormales',
        'puntaje_total',
    ];

    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionIbt::class, 'evaluacion_ibt_id');
    }
}
