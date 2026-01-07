<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManejoResiduo extends Model
{
    use HasFactory;

    protected $table = 'residuos_manejo';

    protected $fillable = [
        'visita_ambiental_id',

        'personas_manipulan',

        'capacita_personal',
        'personas_capacitadas',
        'porcentaje_capacitadas',

        'conoce_diferencias',

        'certificado_final_respel',
        'peso_respel',
        'imagen_certificado_respel',

        'manifiesto_transporte_respel',
        'imagen_manifiesto_respel',

        'puntos_ecologicos',
        'entrega_transportador_aut',
        'disposicion_empresa_aut',
        'acciones_minimizar_impacto',
        'certificado_relleno_sanitario',
        'residuos_aprovechables_gestion',
        'pesa_y_registra',

        'observaciones',
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
