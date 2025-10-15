<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleMobileSessions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
        {
            if ($this->isMobile($request)) {
                config([
                    'session.secure' => false, // Solo para desarrollo
                    'session.same_site' => 'none'
                ]);
            }

            return $next($request);
        }

    private function isMobile($request): bool
        {
            return $request->session()->get('mobile_device', false) || 
                preg_match('/mobile/i', $request->header('User-Agent'));
        }
}
