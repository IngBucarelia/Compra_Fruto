<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboresCultivo extends Model
{
    protected $table = 'labores_cultivo';

    protected $fillable = [
        'visita_id',
        'tipo_planta',
        'polinizacion',
        'limpieza_calle',
        'limpieza_plato',
        'poda',
        'fertilizacion',
        'enmiendas',
        'ubicacion_tusa_fibra',
        'ubicacion_hoja',
        'lugar_ubicacion_hoja',
        'plantas_nectariferas',
        'cobertura',
        'labor_cosecha',
        'calidad_fruta',
        'recoleccion_fruta',
        'drenajes',
        'observaciones'
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
}
