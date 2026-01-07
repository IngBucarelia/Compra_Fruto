<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreVisitaAmbiental extends Model
{
    use HasFactory;

    protected $table = 'cierre_visita_ambientals';

    protected $fillable = [
        'visita_ambiental_id',
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

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class, 'visita_ambiental_id');
    }
}