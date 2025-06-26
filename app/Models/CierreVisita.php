<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreVisita extends Model
{
    use HasFactory;

    protected $fillable = [
        'visita_id',
        'firma_responsable',
        'observaciones_finales',
        'recomendaciones',
        'imagenes',
        'finalizada_en',
        'estado_visita',
    ];

    protected $casts = [
        'imagenes' => 'array',
        'finalizada_en' => 'datetime',
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
}

