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
        'capacitacion_personal',
        'conoce_clasificacion_residuos',
        'certificados_respel',
        'manifiesto_transporte_respel',
        'puntos_ecologicos',
        'entrega_residuos_transportador_autorizado',
        'disposicion_final_empresa_autorizada',
        'acciones_minimizacion_impactos',
        'certificado_disposicion_final',
        'aprovechables_gestionados',
        'pesa_registra_cantidades',
        'observaciones',
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
