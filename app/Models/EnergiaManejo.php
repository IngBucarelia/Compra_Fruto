<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergiaManejo extends Model
{
    protected $table = 'energias_manejos';

    protected $fillable = [
        'visita_ambiental_id',
        'registro_consumo_combustible',
        'plan_uso_eficiente',
        'consumo_energia_kwh',
        'seguimiento_indicadores',
        'observaciones',
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
