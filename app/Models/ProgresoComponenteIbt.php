<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresoComponenteIbt extends Model
{
    protected $table = 'progreso_componentes_ibt';
    
    protected $fillable = [
        'evaluacion_id',
        'componente_id',
        'puntaje_obtenido',
        'puntaje_maximo',
        'completado',
        'fecha_completado',
        'observaciones'
    ];
    
    protected $casts = [
        'completado' => 'boolean',
        'puntaje_obtenido' => 'decimal:2',
        'puntaje_maximo' => 'integer',
        'fecha_completado' => 'date'
    ];
    
    /**
     * Relación: un progreso pertenece a una evaluación
     */
    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionIbt::class, 'evaluacion_id');
    }
    
    /**
     * Relación: un progreso pertenece a un componente
     */
    public function componente()
    {
        return $this->belongsTo(ComponenteIbt::class, 'componente_id');
    }
    
    /**
     * Calcular porcentaje del componente
     */
    public function getPorcentajeAttribute()
    {
        if ($this->puntaje_maximo > 0) {
            return ($this->puntaje_obtenido / $this->puntaje_maximo) * 100;
        }
        return 0;
    }
    
    /**
     * Obtener calificación del componente
     */
    public function getCalificacionAttribute()
    {
        $porcentaje = $this->porcentaje;
        
        if ($porcentaje >= 90) return 'Excelente';
        if ($porcentaje >= 80) return 'Muy Bueno';
        if ($porcentaje >= 70) return 'Bueno';
        if ($porcentaje >= 60) return 'Regular';
        if ($porcentaje > 0) return 'Necesita Mejora';
        return 'Sin calificar';
    }
    
    /**
     * Obtener color de badge según calificación
     */
    public function getBadgeColorAttribute()
    {
        $calificacion = $this->calificacion;
        
        switch ($calificacion) {
            case 'Excelente': return 'success';
            case 'Muy Bueno': return 'primary';
            case 'Bueno': return 'info';
            case 'Regular': return 'warning';
            case 'Necesita Mejora': return 'danger';
            default: return 'secondary';
        }
    }
    
    /**
     * Obtener icono según componente
     */
    public function getIconoAttribute()
    {
        $orden = $this->componente->orden ?? 1;
        
        switch ($orden) {
            case 1: return 'fa-seedling'; // Establecimiento
            case 2: return 'fa-tools';    // Labores culturales
            case 3: return 'fa-flask';    // Manejo nutricional
            case 4: return 'fa-shield-alt'; // Manejo sanitario
            case 5: return 'fa-tractor';  // Cosecha y producción
            default: return 'fa-clipboard-check';
        }
    }
    
    /**
     * Obtener color según componente
     */
    public function getColorComponenteAttribute()
    {
        $orden = $this->componente->orden ?? 1;
        
        switch ($orden) {
            case 1: return '#3498db'; // Azul
            case 2: return '#2ecc71'; // Verde
            case 3: return '#e74c3c'; // Rojo
            case 4: return '#f39c12'; // Naranja
            case 5: return '#9b59b6'; // Púrpura
            default: return '#95a5a6'; // Gris
        }
    }
}