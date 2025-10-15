<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreVisita extends Model
{
    use HasFactory;

    protected $fillable = [
        'visita_id',
        'fecha_cierre',
        'estado_visita',
        'observaciones_finales', 
        'recomendaciones',       
        'firma_responsable',     
        'firma_recibe',
        'firma_testigo',
        'imagenes',
        'finalizada_en',         
    ];

    protected $casts = [
        'fecha_cierre' => 'date',
        'finalizada_en' => 'date', 
        'imagenes' => 'array',
    ];

      public function visita()
    {
        return $this->belongsTo(Visita::class, 'visita_id');
    }
}