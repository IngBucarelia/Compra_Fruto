<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionCosechaCampo extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_cosecha_campo';

    protected $fillable = [
        'visita_id',
        'variedad_fruto',
        'cantidad_racimos',
        'verde',
        'maduro',
        'sobremaduro',
        'pedunculo',
        'observaciones',
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
}
