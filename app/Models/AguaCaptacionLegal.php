<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AguaCaptacionLegal extends Model
{
    protected $fillable = [
        'visita_ambiental_id',
        'permiso_concesion',
        'permiso_ocupacion_cauce',
        'permisos_captacion',
        'registro_agua',
        'cumple_manejo_construccion',
        'gestion_permiso_ocupacion',
        'gestion_permiso_captacion',
        'observaciones'
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
