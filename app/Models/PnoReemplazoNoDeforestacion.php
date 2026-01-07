<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PnoReemplazoNodeforestacion extends Model
{
    protected $table = 'pnoremplazo_nodeforestacion';

    protected $fillable = [
        'visita_ambiental_id',
        'cuenta_estudios_avc_arc',
        'evidencias_no_reemplazo_bosques',
        'permiso_aprovechamiento_forestal',
        'restauracion_compensacion',
        'hectareas_restauracion',
        'fecha_restauracion',
        'tipo_restauracion',
        'otro_tipo_restauracion',
        'ubicacion_restauracion',
        'porcentaje_restauracion',
        'dentro_frontera_agricola',
        'observaciones'
    ];

    protected $casts = [
        'fecha_restauracion' => 'date',
        'hectareas_restauracion' => 'decimal:2',
        'porcentaje_restauracion' => 'decimal:2'
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}