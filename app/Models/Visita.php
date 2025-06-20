<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
    {
        protected $fillable = [
        'fecha',
        'proveedor_id',
        'plantacion_id',
        'ubicacion',
        'tecnico_campo',
        'tipo_visita',
        'recibio_visita',
        'planificacion_id'
    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\Models\Proveedor::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(\App\Models\User::class, 'tecnico_campo');
    }

    public function plantacion()
    {
        return $this->belongsTo(\App\Models\Plantacion::class, 'plantacion_id');
    }
    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class);
    }
    public function area()
    {
        return $this->hasOne(Area::class);
    }

    public function fertilizaciones()
    {
        return $this->hasMany(Fertilizacion::class);
    }



}
