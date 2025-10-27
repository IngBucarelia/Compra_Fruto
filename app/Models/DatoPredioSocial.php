<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoPredioSocial extends Model
{
    use HasFactory;

    protected $table = 'datos_predio_sociales';

    protected $fillable = [
        'visita_social_id',
        'plantacion_id',
        'nombre_finca',
        'forma_tenencia', 
        'municipio',
        'vereda',
        'registrado_ica',
        'vive_predio',
        'infraestructura_vial', // json para múltiples opciones
        'infraestructura_predio',
    ];
     protected $casts = [
    'infraestructura_vial' => 'array',
    'forma_tenencia' => 'array',
    'servicios_publicos' => 'array',
];


    public function visitaSocial()
    {
        return $this->belongsTo(VisitaSocial::class, 'visita_social_id');
    }

    public function plantacion()
    {
        return $this->belongsTo(Plantacion::class, 'plantacion_id');
    }
    
}
