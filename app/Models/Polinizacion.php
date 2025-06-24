<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Polinizacion extends Model
{
    protected $table = 'polinizaciones';

    protected $fillable = [
        'visita_id',
        'n_pases',
        'ciclos_ronda',
        'ana',
        'tipo_ana',
        'talco',
        'fecha',
    ];

    public function visita()
    {
        return $this->belongsTo(\App\Models\Visita::class);
    }

}
