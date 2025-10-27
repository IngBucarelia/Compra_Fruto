<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VisitasSocialCompletasImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'datos_personales_socials' => new DatosPersonalesSocialImport(),
            'miembros_hogar' => new MiembroHogarImport(),
            'datos_predio_sociales' => new DatoPredioSocialImport(),
            'fuerza_laborals' => new FuerzaLaboralImport(),
            'organizacion_social' => new OrganizacionSocialImport(),
            'cierre_visita_socials' => new CierreVisitaSocialImport(),
        ];
    }
}
