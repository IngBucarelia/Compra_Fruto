<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planificacion extends Model
{
    protected $table = 'planificaciones'; 

    protected $fillable = [
        'fecha', 'tecnico_campo', 'proveedor_id', 'plantacion_id', 'tipo_visita', 'estado','visita_id'
    ];

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
        return $this->belongsTo(User::class, 'tecnico_campo');
    }
    public function visita()
    {
        return $this->hasOne(Visita::class);
    }


}
