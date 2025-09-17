<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizacionSocial extends Model
{
    use HasFactory;

    protected $table = 'organizacion_social';

    protected $fillable = [
        'visita_id',
        'pertenece_jac',
        'pertenece_asociacion',
        'nombre_asociacion',
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaSocial::class, 'visita_id');
    }
}
