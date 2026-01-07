<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VertimientoManejo extends Model
{
    protected $table = 'vertimientos_manejos';

    protected $fillable = [
        'visita_ambiental_id',
        'permiso_vertimiento',
        'numero_vertimientos_permitidos',
        'numero_vertimientos_totales',
        'sistemas_tratamiento_domestico',
        'sistemas_tratamiento_agroquimicos',
        'cumple_obligacion_permiso',
        'gestion_permiso_vertimiento',
        'realiza_triplelavado',
        'observaciones',
    ];

    // **AGREGA CASTS EXPLÍCITOS - IMPORTANTE**
    protected $casts = [
        'visita_ambiental_id' => 'integer',
        'permiso_vertimiento' => 'integer', // O 'boolean' si cambias el tipo de columna
        'sistemas_tratamiento_domestico' => 'integer',
        'sistemas_tratamiento_agroquimicos' => 'integer',
        'cumple_obligacion_permiso' => 'integer',
        'gestion_permiso_vertimiento' => 'integer',
        'realiza_triplelavado' => 'integer',
        'numero_vertimientos_permitidos' => 'integer',
        'numero_vertimientos_totales' => 'integer',
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}