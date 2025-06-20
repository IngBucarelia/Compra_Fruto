<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantacion extends Model
{
    protected $table = 'plantaciones'; 
    protected $fillable = [
        'id_proveedor',
        'nombre',
        'vereda',
        'municipio',
        'corregimiento',
        'departamento',
        'geolocalizacion',
        'dia_creado'
    ];
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }
    public function visitas()
    {
        return $this->hasMany(\App\Models\Visita::class, 'plantacion_id');
    }

}
