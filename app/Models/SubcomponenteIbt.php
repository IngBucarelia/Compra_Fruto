<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubcomponenteIbt extends Model
{
    protected $table = 'subcomponentes_ibt';
    
    protected $fillable = [
        'componente_id', 'codigo', 'nombre', 'descripcion',
        'condicion_deseable', 'puntaje_maximo', 'orden'
    ];
    
    public function componente()
    {
        return $this->belongsTo(ComponenteIbt::class, 'componente_id');
    }
}