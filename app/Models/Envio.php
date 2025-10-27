<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;

    protected $fillable = [
        'proveedor_id',
        'plantacion_id',
        'tecnico_id',
        'fecha_envio',
        'descripcion_envio',
        'estado',
        'comentarios_finales',
        'firma_envio',
    ];

    protected $casts = [
        'fecha_envio' => 'date',
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
        return $this->belongsTo(\App\Models\User::class, 'tecnico_id');
    }

    public function evidencias()
    {
    return $this->hasMany(EnvioEvidencia::class, 'envio_id');
    }
}
