<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:8',
                'num_documento' => 'required|numeric',
                'area_pertenece' => 'required|string',
                'ocupacion' => 'required|string',
                'rol' => 'required|numeric',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'num_documento' => $request->num_documento,
                'area_pertenece' => $request->area_pertenece,
                'ocupacion' => $request->ocupacion,
                'rol' => $request->rol,
                'dia_creado' => now(), // Fecha automática
            ]);

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('dashboard'); // Redirige al dashboard después del registro
        }
}
