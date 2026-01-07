<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionIbt extends Model
{
    protected $table = 'evaluaciones_ibt';
    
    protected $fillable = [
        'visita_id', 'proveedor_id', 'plantacion_id', 
        'fecha_evaluacion', 'tecnico_id', 'puntaje_total',
        'calificacion', 'observaciones'
    ];
    
    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
    
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    
    public function plantacion()
    {
        return $this->belongsTo(Plantacion::class);
    }
    
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
    
    public function respuestas()
    {
        return $this->hasMany(RespuestaIbt::class, 'evaluacion_id');
    }
    
    public function componentePuntajes()
    {
        return $this->hasMany(Componenteibt::class, 'evaluacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // componentes de la evaluacion ibt 

    public function establecimientoCultivo()
    {
        return $this->hasOne(
            \App\Models\IbtEstablecimientoCultivo::class,
            'evaluacion_ibt_id'
        );
    }
    
    public function laboresCulturales()
    {
        return $this->hasOne(
            IbtLaboresCulturales::class,
            'evaluacion_ibt_id'
        );
    }

    public function manejoNutricional()
    {
        return $this->hasOne(IbtManejoNutricional::class, 'evaluacion_ibt_id');
    }

    public function manejoSanitario()
    {
        return $this->hasOne(IbtManejoSanitario::class, 'evaluacion_ibt_id');
    }

    public function cosechaProduccion()
    {
        return $this->hasOne(IbtCosechaProduccion::class, 'evaluacion_ibt_id');
    }

    public function calcularPuntajeTotal()
    {
        return collect([
            $this->establecimientoCultivo?->puntaje_total ?? 0,
            $this->laboresCulturales?->puntaje_total ?? 0,
            $this->manejoNutricional?->puntaje_total ?? 0,
            $this->manejoSanitario?->puntaje_total ?? 0,
            $this->cosechaProduccion?->puntaje_total ?? 0,
        ])->sum();
    }

    






}
