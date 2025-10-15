<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sanidad extends Model
{
    protected $table = 'sanidades';

    protected $fillable = [
        'visita_id',
        'local_id',
        'otros',
        'observaciones',
        'censo_enfermedades',
        'ciclos_lectura_enfermedades',
        'ciclos_lectura_plagas',
        'enfermedad',
        'estado_enfermedad',
        'plaga',
        'estado_plaga',
    ];

    /**
     * Relación: una sanidad pertenece a una visita
     */
    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }

    /**
     * Relación: una sanidad puede tener varias trampas
     */
    public function trampas(): HasMany
    {
        return $this->hasMany(TrampaPalmarum::class);
    }

   public function enfermedades()
    {
        return $this->hasMany(SanidadEnfermedad::class, 'sanidad_id', 'id');
    }

    public function plagas()
    {
        return $this->hasMany(SanidadPlaga::class, 'sanidad_id', 'id');
    }


}
