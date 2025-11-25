<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantacionAVC extends Model
{
    protected $table = 'plantacion_avc';

    protected $fillable = [
        'visita_ambiental_id',
        'registros_avistamientos',
        'identifica_avc_arc',
        'implementa_medidas_manejo',
        'observaciones'
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaAmbiental::class, 'visita_ambiental_id');
    }
}
