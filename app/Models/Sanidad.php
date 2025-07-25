<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanidad extends Model
{
    protected $table = 'sanidades';

    protected $fillable = [
        'visita_id',
        'local_id',
        'opsophanes',
        'pudricion_cogollo',
        'raspador',
        'palmarum',
        'strategus',
        'leptoparsha',
        'pestalotiopsis',
        'pudricion_basal',
        'pudricion_estipe',
        'otros',
        'observaciones',
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
}
