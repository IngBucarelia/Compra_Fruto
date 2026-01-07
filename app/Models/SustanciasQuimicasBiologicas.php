<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SustanciasQuimicasBiologicas extends Model
{
    protected $table = 'sustancias_quimicas_biologicas';

    protected $fillable = [
        'visita_ambiental_id',
        'cuenta_poes',
        'personal_capacitado',
        'almacenamiento_adecuado',
        'imagen_poes',
        'observaciones',
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
