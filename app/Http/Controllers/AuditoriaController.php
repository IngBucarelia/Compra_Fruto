<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Auditoria::with('usuario')
            ->orderBy('created_at', 'desc');

        if ($request->ajax()) {
            $search = $request->get('search');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('tipo_accion', 'like', "%$search%")
                      ->orWhere('tipo_modulo', 'like', "%$search%")
                      ->orWhere('descripcion', 'like', "%$search%")
                      ->orWhere('registro_id', 'like', "%$search%")
                      ->orWhereHas('usuario', function ($sub) use ($search) {
                          $sub->where('name', 'like', "%$search%");
                      });
                });
            }
            $auditorias = $query->paginate(15);
            return view('auditorias.index', compact('auditorias'))->render();
        }

        $auditorias = $query->paginate(15);
        return view('auditorias.index', compact('auditorias'));
    }
}
