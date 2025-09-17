<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreVisitaSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'visita_social_id',
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

    public function visitaSocial()
    {
        return $this->belongsTo(VisitaSocial::class, 'visita_social_id');
    }
}
