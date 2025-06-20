<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
   protected $fillable = [
    'visita_id',
    'material',
    'estado',
    'anio_siembra',
    'area',
    'orden_plantis_numero',
    'estado_orden_plantis',
];

public function visita()
{
    return $this->belongsTo(Visita::class);
}


}
