<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Las rutas que deben ser excluidas de la verificación CSRF.
     */
    protected $except = [
        '/api/sync-areas',
        '/sync-fertilizaciones',
        '/polinizaciones',
        '/sanidades',
        '/suelos',
        '/labores-cultivo',
        '/evaluacion-cosecha',
       
    ];
}
