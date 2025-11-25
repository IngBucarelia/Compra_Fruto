<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VertimientoManejo extends Model
{
    protected $table = 'vertimientos_manejos';

    protected $fillable = [
        'visita_ambiental_id',
        'permiso_vertimiento',
        'sistemas_tratamiento_domestico',
        'sistemas_tratamiento_agroquimicos',
        'cumple_obligacion_permiso',
        'gestion_permiso_vertimiento',
        'realiza_triplelavado',
        'observaciones'
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
