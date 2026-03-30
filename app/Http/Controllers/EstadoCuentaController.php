<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Factura;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class EstadoCuentaController extends Controller
{
    /**
     * Muestra la vista de estado de cuenta
     */
    public function index(Request $request)
    {
        $torres = Apartamento::distinct()->pluck('torre');
        $meses = Factura::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes_anio')
                        ->groupBy('mes_anio')
                        ->orderByDesc('mes_anio')
                        ->pluck('mes_anio');

        $apartamentos = collect();
        
        $torreSeleccionada = $request->get('torre');
        $mesSeleccionado = $request->get('mes');

        // Solo cargamos si han seleccionado ambas opciones
        if ($torreSeleccionada && $mesSeleccionado) {
            $apartamentos = Apartamento::where('torre', $torreSeleccionada)
                ->orderBy('numero', 'asc')
                ->get()
                ->map(function ($apto) use ($mesSeleccionado) {
                    $saldoPendienteMes = Factura::where('apartamento_id', $apto->id)
                        ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$mesSeleccionado])
                        ->sum('saldo_pendiente');

                    $apto->deuda_mes = $saldoPendienteMes;
                    return $apto;
                });
        }

        return view('estado_cuenta.index', compact('torres', 'meses', 'apartamentos', 'torreSeleccionada', 'mesSeleccionado'));
    }

    /**
     * Imprime el estado de cuenta a PDF
     */
    public function imprimir(Request $request)
    {
        $torreSeleccionada = $request->get('torre');
        $mesSeleccionado = $request->get('mes');

        if (!$torreSeleccionada || !$mesSeleccionado) {
            return redirect()->route('estado_cuenta.index')->with('error', 'Debe seleccionar una torre y mes para imprimir el estado de cuenta.');
        }

        $apartamentos = Apartamento::where('torre', $torreSeleccionada)
            ->orderBy('numero', 'asc')
            ->get()
            ->map(function ($apto) use ($mesSeleccionado) {
                $saldoPendienteMes = Factura::where('apartamento_id', $apto->id)
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$mesSeleccionado])
                    ->sum('saldo_pendiente');

                $apto->deuda_mes = $saldoPendienteMes;
                return $apto;
            });

        $pdf = Pdf::loadView('estado_cuenta.pdf', compact('apartamentos', 'torreSeleccionada', 'mesSeleccionado', 'request'));
        return $pdf->stream('Estado_Cuenta_Torre_' . $torreSeleccionada . '_Mes_' . $mesSeleccionado . '.pdf');
    }
}
