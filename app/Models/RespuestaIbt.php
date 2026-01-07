<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespuestaIbt extends Model
{
    protected $table = 'respuestas_ibt';
    
    protected $fillable = [
        'evaluacion_id', 'subcomponente_id', 'calificacion_actual',
        'observaciones', 'evidencias'
    ];
    
    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionIbt::class, 'evaluacion_id');
    }
    
    public function subcomponente()
    {
        return $this->belongsTo(SubcomponenteIbt::class, 'subcomponente_id');
    }
}