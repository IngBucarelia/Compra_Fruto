<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantacionHmp extends Model
{
    protected $fillable = [
        'visita_ambiental_id',
        'hectareas_hmp',
        'implementa_hmp',
        'porcentaje_hmp',
        'incluye_hmp_disenio',
        'observaciones',
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
