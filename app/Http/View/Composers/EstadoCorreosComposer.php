<?php

namespace App\Http\View\Composers;

use App\Models\LogCorreo;
use Illuminate\View\View;

class EstadoCorreosComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'correosEnviadosHoy'  => LogCorreo::enviadosHoy(),
            'correosPendientesGlobal' => LogCorreo::totalPendientes(),
            'limiteCorreosDiario' => LogCorreo::LIMITE_DIARIO,
        ]);
    }
}
