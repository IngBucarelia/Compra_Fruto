<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmisionesGei extends Model
{
    protected $table = 'emisiones_gei';

    protected $fillable = [
        'visita_ambiental_id',
        'cuantifica_emisiones',
        'implementa_acciones_reduccion',
        'observaciones'
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaAmbiental::class, 'visita_ambiental_id');
    }
}
