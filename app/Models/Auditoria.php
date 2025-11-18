<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditorias';
    public $timestamps = false; // Solo usamos created_at
    protected $fillable = [
        'usuario_id',
        'tipo_accion',
        'tipo_modulo',
        'registro_id',
        'descripcion',
        'ip',
        'user_agent',
        'created_at'
    ];
     protected $casts = [
        'created_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    
}
