<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManejoSustancia extends Model
{
    protected $fillable = [
        'visita_ambiental_id',
        'cuenta_con_poes',
        'personal_capacitado_certificado',
        'almacenamiento_adecuado',
        'observaciones'
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
