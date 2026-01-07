<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanidadPlaga extends Model
{
    use HasFactory;
    protected $table = 'sanidad_plaga'; 

    protected $fillable = ['sanidad_id', 'nombre_plaga', 'estado','instar'];

    public function sanidad()
    {
        return $this->belongsTo(Sanidad::class);
    }
}

