<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GobernanzaHidrica extends Model
{
    protected $table = 'gobernanza_hidrica';

    protected $fillable = [
        'visita_id',
        'canales_comunicacion',
        'identifica_actores_afectados',
        'participa_actividades_gestion',
        'observaciones'
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaAmbiental::class, 'visita_id');
    }
}
