<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantacionAVC extends Model
{
    protected $table = 'plantacion_avc';

   // En app/Models/PlantacionAvc.php
    protected $fillable = [
        'visita_ambiental_id',
        'registros_avistamientos',
        'identifica_avc_arc',
        'especies_identificadas',
        'fecha_identificacion',
        'ubicacion_identificacion',
        'tipo_identificacion',
        'implementa_medidas_manejo',
        'observaciones'
    ];

    protected $casts = [
        'tipo_identificacion' => 'array',
        'fecha_identificacion' => 'date'
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaAmbiental::class, 'visita_ambiental_id');
    }
}
