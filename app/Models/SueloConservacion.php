<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SueloConservacion extends Model
{
    use HasFactory;
    protected $table = 'suelo_conservacions';

    protected $fillable = [
        'visita_ambiental_id',
        'usa_fuego_preparacion',
        'control_coberturas_invasoras',
        'siembra_coberturas',
        'sigue_recomendaciones_comerciales',
        'observaciones'
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
