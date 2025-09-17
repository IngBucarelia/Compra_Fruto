<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuerzaLaboral extends Model
{
    use HasFactory;

    protected $fillable = [
        'visita_social_id',
        'forma_contratacion',
        'num_trabajadores',
        'num_hombres',
        'num_mujeres',
        'contrato_formal',
        'seguridad_social',
        'tipo_contrato',
        'contrato_firmado',
        'sg_sst',
        'examenes_medicos',
        'trabajadores_migrantes',
        'comprobantes_pago',
        'dotacion',
    ];

    protected $casts = [
        'forma_contratacion' => 'array',
    ];

    public function visitaSocial()
    {
        return $this->belongsTo(VisitaSocial::class, 'visita_social_id');
    }
}
