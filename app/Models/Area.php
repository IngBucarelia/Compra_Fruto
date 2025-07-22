<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'visita_id',
        'material',
        'estado',
        'anio_siembra',
        'area',
        'orden_plantis_numero', 
        'estado_oren_plantis',  
        'area_total_finca_hectareas',
        'numero_palmas_total_finca',
        'area_palmas_desarrollo_hectareas',
        'numero_palmas_desarrollo',
        'area_palmas_produccion_hectareas',
        'numero_palmas_produccion',
        'ciclos_cosecha',
        'produccion_toneladas_por_mes',
        'aplica_orden_plantis', 
        'numero_plantas_orden_plantis', 
    ];

    protected $casts = [
        'anio_siembra' => 'date',
        'area_total_finca_hectareas' => 'decimal:2',
        'area_palmas_desarrollo_hectareas' => 'decimal:2',
        'area_palmas_produccion_hectareas' => 'decimal:2',
        'produccion_toneladas_por_mes' => 'decimal:2',
        'aplica_orden_plantis' => 'boolean', // Castear el nuevo campo booleano
    ];

    // Relación con Visita
    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
}