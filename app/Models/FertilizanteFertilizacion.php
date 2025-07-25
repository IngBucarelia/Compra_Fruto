<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertilizanteFertilizacion extends Model
{
    protected $table = 'fertilizante_fertilizacion';

    protected $fillable = [
        'fertilizacion_id',
        'local_id',
        'fertilizante', 
        'cantidad',
        'fecha_aplicacion', 
        'unidad_medida'     
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date', // ✅ Castear a tipo fecha
    ];
    

    public function fertilizacion()
    {
        return $this->belongsTo(Fertilizacion::class, 'fertilizacion_id', 'id');
    }
}
