<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request)
    {
       try {
            $request->authenticate();
            $request->session()->regenerate();

            $redirectUrl = route('dashboard');

            if ($request->wantsJson() || $this->isMobileDevice($request)) {
                return response()->json([
                    'redirect' => $redirectUrl,
                    'session_token' => csrf_token()
                ]);
            }

            return redirect()->intended($redirectUrl);

        } catch (\Exception $e) {
            if ($request->wantsJson() || $this->isMobileDevice($request)) {
                return response()->json([
                    'error' => 'Credenciales incorrectas'
                ], 401);
            }
            
            throw $e;
        }
    }

    // Métodos auxiliares
    protected function isMobileDevice($request): bool
    {
           return preg_match('/mobile/i', $request->header('User-Agent'));

    }

protected function getSafeRedirectUrl($request): string
{
    $default = route('dashboard');
    $intended = $request->session()->get('url.intended', $default);
    
    // Validar que la URL sea segura y pertenezca a tu dominio
    if (!Str::startsWith($intended, config('app.url'))) {
        return $default;
    }
    
    return $intended;
}

protected function handleLoginError($request, $e)
{
    if ($this->isMobileDevice($request)) {
        return response()->json([
            'error' => 'Credenciales incorrectas'
        ], 401);
    }
    
    throw $e; // Laravel manejará el resto
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
