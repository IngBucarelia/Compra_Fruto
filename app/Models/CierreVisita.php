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
        'firma_recibe',
        'firma_testigo',
        'imagenes',
        'observaciones_finales',
        'recomendaciones',
        'estado_visita',
        'finalizada_en',
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

