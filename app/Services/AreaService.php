<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Visita;

class AreaService
{
    public static function obtenerAreaTotalFinca($visitaAmbiental)
    {
        $areaTotalFinca = 0;
        
        if (!$visitaAmbiental->plantacion_id) {
            return $areaTotalFinca;
        }
        
        // Buscar última visita a la misma plantación
        $ultimaVisita = Visita::where('plantacion_id', $visitaAmbiental->plantacion_id)
            ->latest('fecha')
            ->first();
            
        if (!$ultimaVisita) {
            return $areaTotalFinca;
        }
        
        // Buscar área de esa visita
        $area = Area::where('visita_id', $ultimaVisita->id)->first();
        
        if (!$area) {
            return $areaTotalFinca;
        }
        
        // Nombres posibles de campos de área
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
                $areaTotalFinca = (float) $area->$campo;
                break;
            }
        }
        
        return $areaTotalFinca;
    }
    
    public static function obtenerInfoCompleta($visitaAmbiental)
    {
        $info = [
            'area_total_finca' => 0,
            'ultima_visita' => null,
            'fecha_visita' => null,
            'mensaje' => ''
        ];
        
        if (!$visitaAmbiental->plantacion_id) {
            $info['mensaje'] = 'La visita ambiental NO tiene plantación_id';
            return $info;
        }
        
        $ultimaVisita = Visita::where('plantacion_id', $visitaAmbiental->plantacion_id)
            ->latest('fecha')
            ->first();
            
        if (!$ultimaVisita) {
            $info['mensaje'] = 'NO se encontraron visitas para esta plantación';
            return $info;
        }
        
        $info['ultima_visita'] = $ultimaVisita;
        $info['fecha_visita'] = $ultimaVisita->fecha;
        
        $area = Area::where('visita_id', $ultimaVisita->id)->first();
        
        if (!$area) {
            $info['mensaje'] = 'NO se encontró área para esta visita';
            return $info;
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
                $info['area_total_finca'] = (float) $area->$campo;
                $info['campo_usado'] = $campo;
                $info['mensaje'] = "Área obtenida del campo '{$campo}'";
                break;
            }
        }
        
        if ($info['area_total_finca'] <= 0) {
            $info['mensaje'] = 'No se encontró campo de área total con valor positivo';
        }
        
        return $info;
    }
}