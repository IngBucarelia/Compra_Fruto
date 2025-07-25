<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fertilizacion extends Model
{
        protected $table = 'fertilizaciones';

    protected $fillable = ['visita_id', 'local_id', 'fecha_fertilizacion'];

    public function detalles()
        {
            return $this->hasMany(FertilizanteFertilizacion::class);
        }


    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
    public function fertilizantes()
        {
            return $this->hasMany(\App\Models\FertilizanteFertilizacion::class);
        }

}
