<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SueloConservacion extends Model
{
    use HasFactory;
    protected $table = 'suelo_conservacions';

    protected $fillable = [
       'visita_ambiental_id',
        'uso_fuego_preparacion',
        'control_coberturas_invasoras',
        'siembra_coberturas',
        'sigue_recomendaciones_comerciales',
        'area_cobertura',
        'tipo_cobertura',
        'otro_tipo_cobertura',
        'porcentaje_cobertura',
        'observaciones'
    ];

    protected $casts = [
        'uso_fuego_preparacion' => 'boolean',
        'control_coberturas_invasoras' => 'boolean',
        'siembra_coberturas' => 'boolean',
        'sigue_recomendaciones_comerciales' => 'boolean',
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
