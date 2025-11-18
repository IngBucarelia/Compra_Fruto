<?php

namespace App\Helpers;

use App\Models\Auditoria;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuditoriaHelper
{
    /**
     * Registra un movimiento en la tabla de auditoría
     */
    public static function registrar($tipoAccion, $tipoModulo, $registroId = null, $descripcion = null)
    {
        Auditoria::create([
            'usuario_id' => Auth::id(),
            'tipo_accion' => $tipoAccion,
            'tipo_modulo' => $tipoModulo,
            'registro_id' => $registroId,
            'descripcion' => $descripcion,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => Carbon::now('America/Bogota')->toDateTimeString(),
        ]);
    }
}
