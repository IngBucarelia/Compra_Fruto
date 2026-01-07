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
        'proveedor_id',
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
    
    public function vertimientoManejo()
    {
        return $this->hasOne(VertimientoManejo::class);
    }

    public function vertimientosManejo()
    {
        return $this->hasOne(VertimientoManejo::class);
    }

    
    public function hmpManejo() 
    { 
        return $this->hasOne(HmpManejo::class, 'visita_ambiental_id'); 
    }
    


    public function plantacionHmp() 
    { 
        return $this->hasOne(PlantacionHmp::class, 'visita_ambiental_id'); 
    }


    public function avcControl() 
    { 
        return $this->hasOne(AvcControl::class, 'visita_ambiental_id'); 
    }
     public function plantacionAvc() 
    { 
        return $this->hasOne(PlantacionAVC::class, 'visita_ambiental_id'); 
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

    public function obtenerAreaTotalFinca()
    {
        if (!$this->plantacion_id) {
            return 0;
        }
        
        $ultimaVisita = Visita::where('plantacion_id', $this->plantacion_id)
            ->latest('fecha')
            ->first();
            
        if (!$ultimaVisita) {
            return 0;
        }
        
        $area = Area::where('visita_id', $ultimaVisita->id)->first();
        
        if (!$area) {
            return 0;
        }
        
        $posiblesCamposArea = [
            'area_total_finca_hectareas',
            'area_total_finca',
            'area_total', 
            'total_area',
            'area',
            'hectareas',
            'area_hectareas',
            'area_total_hectareas'
        ];
        
        foreach ($posiblesCamposArea as $campo) {
            if (isset($area->$campo) && !empty($area->$campo) && $area->$campo > 0) {
                return (float) $area->$campo;
            }
        }
        
        return 0;
    }

    public function pnoReemplazoNodeforestacion()
    {
        return $this->hasOne(PnoReemplazoNodeforestacion::class, 'visita_ambiental_id');
    }

    public function plantacionEcosistema()
    {
        return $this->hasOne(PlantacionEcosistema::class, 'visita_ambiental_id');
    }

    public function cierreVisitaAmbiental()
    {
        return $this->hasOne(CierreVisitaAmbiental::class, 'visita_ambiental_id');
    }




   
}