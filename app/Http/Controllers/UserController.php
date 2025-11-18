<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::where('estado', 'activo')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }


    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'num_documento' => 'required|string|max:30|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'area_pertenece' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'rol' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'num_documento' => $request->num_documento,
            'area_pertenece' => $request->area_pertenece,
            'ocupacion' => $request->ocupacion,
            'rol' => $request->rol,
            'password' => Hash::make($request->password),
            'dia_creado' => now(),
        ]);

        // 🔹 Auditoría
        Auditoria::create([
            'usuario_id' => Auth::id(),
            'modulo' => 'Usuarios',
            'tipo_accion' => 'Crear usuario',
            'descripcion' => "Se creó el usuario {$user->name} ({$user->email})"
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    // 🔹 Actualizar usuario + auditoría
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$id}",
            'num_documento' => "required|string|max:30|unique:users,num_documento,{$id}",
            'area_pertenece' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'rol' => 'nullable|string|max:50',
        ]);

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'num_documento' => $request->num_documento,
            'area_pertenece' => $request->area_pertenece,
            'ocupacion' => $request->ocupacion,
            'rol' => $request->rol,
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
            $usuario->save();
        }

        Auditoria::create([
            'usuario_id' => Auth::id(),
            'tipo_modulo' => 'Usuarios',
            'tipo_accion' => 'Editar usuario',
            'descripcion' => "Se editó el usuario {$usuario->name} ({$usuario->email})",
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // 🔹 Inhabilitar usuario (no borrar)
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->estado = $usuario->estado === 'inactivo' ? 'activo' : 'inactivo';
        $usuario->save();

        Auditoria::create([
            'usuario_id' => Auth::id(),
            'tipo_modulo' => 'Usuarios',
            'tipo_accion' => 'Cambio de estado',
            'descripcion' => "Se cambió el estado del usuario {$usuario->name} a {$usuario->estado}",
        ]);

        return redirect()->back()->with('success', "Estado del usuario cambiado a {$usuario->estado}");
    }
}
