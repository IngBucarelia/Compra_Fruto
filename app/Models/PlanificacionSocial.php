<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanificacionSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'tecnico_campo',
        'proveedor_id',
        'plantacion_id',
        'tipo_visita',
        'estado',
        'visita_social_id'
    ];

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_campo');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function plantacion()
    {
        return $this->belongsTo(Plantacion::class);
    }

    public function visitaSocial()
    {
        return $this->belongsTo(VisitaSocial::class);
    }
    public function visita()
{
    return $this->hasOne(VisitaSocial::class, 'planificacion_id');
}

    
}