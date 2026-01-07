<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AguaUsoEficiente extends Model
{
    protected $table = 'agua_uso_eficientes';

    protected $fillable = [
        'visita_ambiental_id',
        'plan_ahorro',
        'mantenimiento_sistemas',
        'uso_informacion_balance',
        'mecanismo_medicion',
        'consumo_agua',
        'metodo_medicion',
        'observaciones'
    ];

    public function visitaAmbiental()
    {
        return $this->belongsTo(VisitaAmbiental::class);
    }
}
