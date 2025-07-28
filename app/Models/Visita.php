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
        'planificacion_id',
        'estado'
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
    // Para relación uno-a-muchos (una visita tiene muchas áreas)
public function areas()
{
    return $this->hasMany(Area::class);
}

// O si es uno-a-uno (una visita tiene un área)
public function area()
{
    return $this->hasOne(Area::class);
}

    public function fertilizaciones()
    {
        return $this->hasMany(Fertilizacion::class);
    }
    // app/Models/Visita.php

    public function polinizaciones()
    {
        return $this->hasMany(\App\Models\Polinizacion::class);
    }

    public function sanidad()
    {
        return $this->hasOne(Sanidad::class);
    }

    public function suelo()
    {
        return $this->hasOne(Suelo::class);
    }

    public function sanidades()
    {
        return $this->hasMany(Sanidad::class);
    }

    public function laboresCultivo()
    {
        return $this->hasMany(LaboresCultivo::class);
    }


    // app/Models/Visita.php

    public function evaluacionCosechaCampo()
    {
               return $this->hasMany(EvaluacionCosechaCampo::class);

    }

    public function cierreVisita()
    {
        return $this->hasOne(CierreVisita::class);
    }










}
