<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembroHogar extends Model
{
    use HasFactory;

    protected $table = 'miembros_hogar';

    protected $fillable = [
        'visita_social_id',
        'nombre',
        'documento',
        'sexo',
        'parentezco',
        'reside_predio',
        'sabe_leer',
        'nivel_estudio',
        'participa_labores',
    ];

    public function visitaSocial()
    {
        return $this->belongsTo(VisitaSocial::class, 'visita_social_id');
    }
}
