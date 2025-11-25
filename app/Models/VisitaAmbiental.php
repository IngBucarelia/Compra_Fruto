<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaAmbiental extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_visita',
        'tecnico_id', 
        'plantacion_id',
        'ubicacion',
        'observaciones',
        'estado',
        'planificacion_id', // Si también manejas planificación ambiental
    ];

    // Relaciones principales (igual que VisitaSocial)
    public function plantacion()
    {
        return $this->belongsTo(Plantacion::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    // Si también tienes proveedores en ambiental
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Si manejas planificación ambiental
    public function planificacion()
    {
        return $this->belongsTo(PlanificacionSocial::class, 'planificacion_id');
    }

    // Relaciones 1:1 con cada componente ambiental (igual estructura que VisitaSocial)
    public function aguaCaptacionLegal() 
    { 
        return $this->hasOne(AguaCaptacionLegal::class, 'visita_ambiental_id'); 
    }
    
    public function aguaUsoEficiente() 
    { 
        return $this->hasOne(AguaUsoEficiente::class, 'visita_ambiental_id'); 
    }
    
    public function sueloConservacion() 
    { 
        return $this->hasOne(SueloConservacion::class, 'visita_ambiental_id'); 
    }
    
    public function energiaManejo()
    {
        return $this->hasOne(EnergiaManejo::class);
    }

    public function energia()
    {
        return $this->hasOne(EnergiaManejo::class);
    }

    public function gobernanzaHidrica()
    {
        return $this->hasOne(GobernanzaHidrica::class, 'visita_id');
    }

   

    
   public function emisionesGei()
    {
        return $this->hasOne(EmisionesGei::class);
    }

    
    public function residuosManejo()
    {
        return $this->hasOne(ManejoResiduo::class, 'visita_ambiental_id');
    }


    
    public function sustanciasManejo() 
    { 
        return $this->hasOne(SustanciasManejo::class, 'visita_ambiental_id'); 
    }
    
    public function vertimientosManejo()
    {
        return $this->hasOne(VertimientoManejo::class);
    }

    
    public function hmpManejo() 
    { 
        return $this->hasOne(HmpManejo::class, 'visita_ambiental_id'); 
    }
    
    public function avcControl() 
    { 
        return $this->hasOne(AvcControl::class, 'visita_ambiental_id'); 
    }
    
    public function ecosistemaProteccion() 
    { 
        return $this->hasOne(EcosistemaProteccion::class, 'visita_ambiental_id'); 
    }
    
    public function avcNoReemplazo() 
    { 
        return $this->hasOne(AvcNoReemplazo::class, 'visita_ambiental_id'); 
    }
    
    public function deforestacionControl() 
    { 
        return $this->hasOne(DeforestacionControl::class, 'visita_ambiental_id'); 
    }

    public function sustanciasQuimicasBiologicas()
    {
        return $this->hasOne(sustanciasQuimicasBiologicas::class);
    }
    public function manejoVertimientos()
    {
        return $this->hasOne(ManejoVertimientos::class);
    }



   
}