<?php

namespace App\Http\Controllers;

use App\Models\AguaUsoEficiente;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class AguaUsoEficienteController extends Controller
{
    public function index()
    {
        $items = AguaUsoEficiente::all();
        return view('agua_uso_eficiente.index', compact('items'));
    }


    public function create($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        return view('agua_uso_eficiente.create', compact('visita'));
    }

    public function store(Request $request, $visita_id)
    {
                
        $visita = VisitaAmbiental::findOrFail($visita_id);

        AguaUsoEficiente::create([
            'visita_ambiental_id' => $visita_id, 
            'plan_ahorro' => $request->plan_ahorro ?? false,
            'mantenimiento_sistemas' => $request->mantenimiento_sistemas ?? false,
            'uso_informacion_balance' => $request->uso_informacion_balance ?? false,
            'mecanismo_medicion' => $request->mecanismo_medicion ?? false,
            'metodo_medicion' =>  $request->metodo_medicion ?? false,
            'consumo_agua' => $request->consumo_agua ?? false,
            'observaciones' => $request->observaciones
        ]);
        return view('suelo_conservacion.create', compact('visita'))->with('success', '✅ Agua Componente de agua uso eficiente guardado correctamente');

        
        }

        public function show(AguaUsoEficiente $aguaUsoEficiente)
        {
            return view('agua_uso_eficiente.show', compact('aguaUsoEficiente'));
        }

        public function edit(AguaUsoEficiente $aguaUsoEficiente)
        {
            return view('agua_uso_eficiente.edit', compact('aguaUsoEficiente'));
        }

        public function update(Request $request, AguaUsoEficiente $aguaUsoEficiente)
        {
            $aguaUsoEficiente->update([
                'plan_ahorro' => $request->plan_ahorro,
                'mantenimiento_sistemas' => $request->mantenimiento_sistemas,
                'uso_informacion_balance' => $request->uso_informacion_balance,
                'mecanismo_medicion' => $request->mecanismo_medicion,
            ]);

            return redirect()->route('agua_uso_eficiente.index')
                ->with('success', 'Componente actualizado correctamente');
        }

        public function destroy(AguaUsoEficiente $aguaUsoEficiente)
        {
            $aguaUsoEficiente->delete();

            return redirect()->route('agua_uso_eficiente.index')
                ->with('success', 'Componente eliminado');
        }
}
