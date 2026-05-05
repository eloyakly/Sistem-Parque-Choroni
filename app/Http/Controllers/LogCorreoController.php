<?php

namespace App\Http\Controllers;

use App\Models\LogCorreo;
use Illuminate\Http\Request;

class LogCorreoController extends Controller
{
    public function index(Request $request)
    {
        $query = LogCorreo::latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        if ($request->filled('correo')) {
            $query->where('destinatario', 'LIKE', '%' . $request->correo . '%');
        }

        $logs = $query->simplePaginate(50);

        $enviadosHoy   = LogCorreo::whereDate('created_at', today())->where('estado', 'enviado')->count();
        $fallidos      = LogCorreo::whereDate('created_at', today())->where('estado', 'fallido')->count();
        $omitidosHoy   = LogCorreo::whereDate('created_at', today())->where('estado', 'omitido')->count();
        $limiteDisponible = max(0, LogCorreo::LIMITE_DIARIO - $enviadosHoy);

        return view('log_correos.index', compact(
            'logs', 'enviadosHoy', 'fallidos', 'omitidosHoy', 'limiteDisponible'
        ));
    }
}
