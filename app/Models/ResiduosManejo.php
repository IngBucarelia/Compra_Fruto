<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResiduosManejo extends Model
{
    protected $table = 'residuos_manejo';

    protected $fillable = [
        'visita_ambiental_id',
        'capacita_personal',
        'personas_manipulan',
        'personas_capacitadas',
        'porcentaje_capacitadas',
        'conoce_diferencias',
        'certificado_final_respel',
        'manifiesto_transporte_respel',
        'puntos_ecologicos',
        'entrega_transportador_aut',
        'disposicion_empresa_aut',
        'acciones_minimizar_impacto',
        'certificado_relleno_sanitario',
        'residuos_aprovechables_gestion',
        'pesa_y_registra',
        'observaciones'
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaAmbiental::class, 'visita_ambiental_id');
    }
}
