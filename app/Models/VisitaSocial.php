<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'tecnico_campo',
        'proveedor_id',
        'plantacion_id',
        'ubicacion',
        'tipo_visita',
        'recibio_visita',
        'estado',
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_campo');
    }

    public function plantacion()
    {
        return $this->belongsTo(Plantacion::class);
    }
}
