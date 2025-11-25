<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'tecnico_campo',
        'proveedor_id',
        'plantacion_id',
        'ubicacion', 
        'tipo_visita',
        'recibio_visita',
        'estado',
        'planificacion_id',
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_campo');
    }

    public function plantacion()
    {
        return $this->belongsTo(Plantacion::class);
    }

    public function datosPersonales()
    {
        return $this->hasOne(DatosPersonalesSocial::class);
    }
    public function miembros()
    {
        return $this->hasMany(MiembroHogar::class, 'visita_social_id');
    }

    public function predio()
    {
        return $this->hasOne(DatoPredioSocial::class, 'visita_social_id');
    }

    public function fuerzaLaboral()
    {
        return $this->hasMany(FuerzaLaboral::class, 'visita_social_id');
    }

    public function organizacionSocial()
    {
        return $this->hasOne(OrganizacionSocial::class, 'visita_id');
    }
    public function cierreVisitaSocial()
    {
        return $this->hasOne(CierreVisitaSocial::class, 'visita_social_id');
    }

    public function planificacion()
    {
        return $this->belongsTo(PlanificacionSocial::class, 'planificacion_id');
    }

}
