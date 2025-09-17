<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = ['proveedor_nombre', 'nit', 'dia_creado'];
    
    public function plantaciones()
    {
        return $this->hasMany(Plantacion::class, 'id_proveedor');
    }
    public function datosPersonalesSociales()
    {
        return $this->hasMany(DatosPersonalesSocial::class);
    }

}