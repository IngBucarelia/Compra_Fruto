<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'visita_id',
        'analisis_foliar',
        'alanisis_suelo',
        'tipo_suelo'
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }
}

