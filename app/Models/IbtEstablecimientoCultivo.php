<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbtEstablecimientoCultivo extends Model
{
    protected $table = 'ibt_establecimiento_cultivo';

    protected $fillable = [
        'evaluacion_ibt_id',
        'estudios_caracterizacion_suelos',
        'estudios_topograficos',
        'diseno_riegos_drenajes',
        'diseno_uma',
        'preparacion_suelos',
        'leguminosas_cobertura',
        'puntaje_total'
    ];

    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionIbt::class, 'evaluacion_ibt_id');
    }
}
