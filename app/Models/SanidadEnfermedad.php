<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanidadEnfermedad extends Model
{
    use HasFactory;
    protected $table = 'sanidad_enfermedad'; 
    protected $fillable = ['sanidad_id', 'nombre_enfermedad', 'estado'];


    public function sanidad()
    {
        return $this->belongsTo(Sanidad::class);
    }
}
