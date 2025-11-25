<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PnoReemplazoNoDeforestacion extends Model
{
    protected $table = 'pnoremplazo_nodeforestacion';

    protected $fillable = [
        'visita_ambiental_id',
        'cuenta_estudios_avc_arc',
        'evidencias_no_reemplazo_bosques',
        'permiso_aprovechamiento_forestal',
        'restauracion_compensacion',
        'dentro_frontera_agricola',
        'observaciones'
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
