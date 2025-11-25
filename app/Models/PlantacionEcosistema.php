<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantacionEcosistema extends Model
{
    protected $table = 'plantacion_ecosistemas';

    protected $fillable = [
        'visita_ambiental_id',
        'planes_manejo_diferenciados',
        'acciones_conservacion_fragmentos',
        'implementa_planes_manejo_diferenciado',
        'respeta_distancias_ronda_hidrica',
        'observaciones'
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaAmbiental::class, 'visita_ambiental_id');
    }
}
