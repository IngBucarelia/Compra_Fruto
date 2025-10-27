<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosPersonalesSocial extends Model
{
    protected $fillable = [
        'visita_social_id','proveedor_id','telefono','sexo','rnp','numero_rnp','fedepalma','alfabetizado',
        'nivel_estudio','otras_lineas','fecha_nacimiento','grupo_poblacional','reside_predio',
        'administra_cultivo','supervisa_cultivo','realiza_cultivo','anios_palmicultura','internet',
        'tipo_persona','red_social','regimen_salud','oferta_mercantil','hace_cuanto'
    ];

    public function visitaSocial()
    {
        return $this->belongsTo(VisitaSocial::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}

