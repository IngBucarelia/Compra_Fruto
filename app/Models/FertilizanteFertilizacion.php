<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertilizanteFertilizacion extends Model
{
    protected $table = 'fertilizante_fertilizacion';

    protected $fillable = [
        'fertilizacion_id', 'fertilizante', 'cantidad'
    ];

    public function fertilizacion()
    {
        return $this->belongsTo(Fertilizacion::class, 'fertilizacion_id', 'id');
    }
}
