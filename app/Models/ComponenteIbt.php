<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponenteIbt extends Model
{
    protected $table = 'componentes_ibt';
    
    protected $fillable = ['nombre', 'descripcion', 'puntaje_maximo', 'orden'];
    
    public function subcomponentes()
    {
        return $this->hasMany(SubcomponenteIbt::class, 'componente_id');
    }
}