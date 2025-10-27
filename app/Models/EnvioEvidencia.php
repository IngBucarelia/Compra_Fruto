<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvioEvidencia extends Model
{
    protected $table = 'envio_evidencias';

    protected $fillable = [
        'envio_id',
        'archivo',
    ];

    public function envio()
    {
        return $this->belongsTo(Envio::class);
    }
}
