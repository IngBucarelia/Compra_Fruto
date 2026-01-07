<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/* MODELOS */
use App\Models\AguaCaptacionLegal;
use App\Models\AguaUsoEficiente;
use App\Models\SueloConservacion;
use App\Models\GobernanzaHidrica;
use App\Models\EmisionesGei;
use App\Models\ResiduosManejo;
use App\Models\SustanciasManejo;
use App\Models\HmpManejo;
use App\Models\AvcControl;
use App\Models\EcosistemaProteccion;
use App\Models\PnoReemplazoNodeforestacion;
use App\Models\CierreVisitaAmbiental;
use App\Models\EnergiaManejo;
use App\Models\ManejoVertimientos;

class AmbientalSyncController extends Controller
{
    /* =====================================================
       MÉTODO BASE REUTILIZABLE
    ====================================================== */
    private function syncGeneric(Request $request, $modelClass)
    {
        $request->validate([
            'submissions' => 'required|array'
        ]);

        $sincronizados = 0;

        DB::beginTransaction();
        try {
            foreach ($request->submissions as $data) {

                $localId = $data['local_id'] ?? null;

                unset($data['local_id']);

                $modelClass::updateOrCreate(
                    ['local_id' => $localId],
                    $data
                );

                $sincronizados++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sincronizados' => $sincronizados
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* =====================================================
       COMPONENTES AMBIENTALES
    ====================================================== */

    public function aguaCaptacion(Request $request)
    {
        return $this->syncGeneric($request, AguaCaptacionLegal::class);
    }

    public function aguaUso(Request $request)
    {
        return $this->syncGeneric($request, AguaUsoEficiente::class);
    }

    public function sueloConservacion(Request $request)
    {
        return $this->syncGeneric($request, SueloConservacion::class);
    }

    public function energia(Request $request)
    {
        return $this->syncGeneric($request, EnergiaManejo::class);
    }

    public function gobernanza(Request $request)
    {
        return $this->syncGeneric($request, GobernanzaHidrica::class);
    }

    public function emisiones(Request $request)
    {
        return $this->syncGeneric($request, EmisionesGei::class);
    }

    public function residuos(Request $request)
    {
        return $this->syncGeneric($request, ResiduosManejo::class);
    }

    public function sustancias(Request $request)
    {
        return $this->syncGeneric($request, SustanciasManejo::class);
    }

    public function vertimientos(Request $request)
    {
        return $this->syncGeneric($request, ManejoVertimientos::class);
    }

    public function hmp(Request $request)
    {
        return $this->syncGeneric($request, HmpManejo::class);
    }

    public function avc(Request $request)
    {
        return $this->syncGeneric($request, AvcControl::class);
    }

    public function ecosistema(Request $request)
    {
        return $this->syncGeneric($request, EcosistemaProteccion::class);
    }

    public function noDeforestacion(Request $request)
    {
        return $this->syncGeneric($request, PnoReemplazoNodeforestacion::class);
    }

    /* =====================================================
       CIERRE VISITA AMBIENTAL
    ====================================================== */
    public function cierreAmbiental(Request $request)
    {
        $request->validate([
            'submissions' => 'required|array'
        ]);

        $sincronizados = 0;

        DB::beginTransaction();
        try {
            foreach ($request->submissions as $data) {

                $localId = $data['local_id'] ?? null;

                unset($data['local_id']);

                // Firmas e imágenes ya vienen en Base64
                CierreVisitaAmbiental::updateOrCreate(
                    ['local_id' => $localId],
                    $data
                );

                $sincronizados++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sincronizados' => $sincronizados
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
