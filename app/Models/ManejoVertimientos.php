<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManejoVertimientos extends Model
{
    protected $table = 'manejo_vertimientos';

    protected $fillable = [
        'visita_ambiental_id',
        'permiso_vertimientos',
        'sistema_agua_domestica',
        'sistema_agua_no_domestica',
        'sistema_agroquimicos',
        'cumple_permiso',
        'gestion_permiso',
        'triple_lavado',
        'observaciones'
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
