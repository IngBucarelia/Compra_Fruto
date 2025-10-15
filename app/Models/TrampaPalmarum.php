<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrampaPalmarum extends Model
{
    protected $table = 'trampas_palmarum';

    protected $fillable = [
        'sanidad_id',
        'ciclos',
        'machos_capturados',
        'hembras_capturadas',
    ];

    /**
     * Get the sanidad that owns the trampa.
     */
    public function sanidad()
    {
        return $this->belongsTo(Sanidad::class);
    }
}
