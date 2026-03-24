<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Apartamento;
use App\Models\GastoMes;
use App\Models\TipoApartamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaEmitidaMail;
use Carbon\Carbon;

class FacturaController extends Controller
{
    /**
     * Listado de facturas.
     */
    public function index(Request $request)
    {
        $query = Factura::with('apartamento.propietario')->latest();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('descripcion', 'LIKE', "%$buscar%")
                  ->orWhere('id', 'LIKE', "%$buscar%")
                  ->orWhereHas('apartamento', function($q) use ($buscar) {
                      $q->where('numero', 'LIKE', "%$buscar%")
                        ->orWhere('torre', 'LIKE', "%$buscar%");
                  });
        }

        $facturas = $query->get();
        return view('facturas.index', compact('facturas'));
    }

    /**
     * Muestra formulario para crear una factura.
     */
    public function create()
    {
        $apartamentos = Apartamento::with(['propietario', 'tipo'])->orderBy('numero')->get();
        // Cargar los gastos con sus detalles para el desglose en JS
        $gastos = GastoMes::with('detalles')->latest()->get();
        
        $tipos = TipoApartamento::orderBy('nombre')->get();

        return view('facturas.crear', compact('apartamentos', 'gastos', 'tipos'));
    }

    /**
     * Generar la factura.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gasto_mes_id'      => 'required|exists:gasto_mes,id',
            'apartamento_id'    => 'required|exists:apartamentos,id',
            'fecha_vencimiento' => 'required|date',
        ], [
            'gasto_mes_id.required'      => 'Debe seleccionar un mes de gastos.',
            'gasto_mes_id.exists'        => 'El mes de gastos seleccionado no es válido.',
            'apartamento_id.required'    => 'Debe seleccionar un apartamento.',
            'apartamento_id.exists'      => 'El apartamento seleccionado no es válido.',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
            'fecha_vencimiento.date'     => 'La fecha de vencimiento no es una fecha válida.',
        ]);

        try {
            DB::beginTransaction();

            $gastoMes = GastoMes::findOrFail($request->gasto_mes_id);
            $apartamento = Apartamento::with('tipo')->findOrFail($request->apartamento_id);

            // Calcular monto
            $alicuota = $apartamento->tipo->alicuota; // Ej: 5.50
            if (is_null($alicuota) || $alicuota <= 0) {
                return redirect()->back()->withInput()->with('error', 'El tipo de apartamento no tiene una alícuota válida configurada mayor a 0.');
            }

            // Regla de 3 o porcentaje (Porcentaje: Gasto * Alicuota / 100)
            $montoTotal = round($gastoMes->total_gastos * ($alicuota / 100), 2);

            // Formatear mes p. ej. "Marzo 2026"
            $mesFormateado = Carbon::parse($gastoMes->mes_anio)->translatedFormat('F Y');
            $descripcion = "Mensualidad de Condominio - " . ucfirst($mesFormateado);

            // Evitar facturas duplicadas para el mismo mes y apartamento
            if (Factura::where('apartamento_id', $apartamento->id)->where('descripcion', $descripcion)->exists()) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Este apartamento ya tiene una factura emitida para este mes (' . $descripcion . ').');
            }

            // Crear Factura
            $factura = Factura::create([
                'apartamento_id'    => $apartamento->id,
                'descripcion'       => $descripcion,
                'monto_total'       => $montoTotal,
                'saldo_pendiente'   => $montoTotal,
                'estado'            => 'no_pagado',
                'fecha_vencimiento' => $request->fecha_vencimiento,
            ]);

            // Actualizar deuda del apartamento sumando la nueva factura
            $apartamento->increment('deuda_actual', $montoTotal);

            // Enviar Correo con Desglose
            if ($apartamento->propietario && $apartamento->propietario->email) {
                Mail::to($apartamento->propietario->email)->send(new FacturaEmitidaMail($factura, $gastoMes));
            }

            // Opcionalmente, marcar el GastoMes como procesado para saber que ya se usó (al menos parcialmente).
            if (!$gastoMes->procesado) {
                $gastoMes->update(['procesado' => true]);
            }

            DB::commit();

            return redirect()->route('facturas.index')
                ->with('exito', 'Factura generada exitosamente. Monto: $ ' . number_format($montoTotal, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al generar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Generar facturas masivamente por Tipo de Apartamento.
     */
    public function storeMasivo(Request $request)
    {
        $request->validate([
            'gasto_mes_id'        => 'required|exists:gasto_mes,id',
            'tipo_apartamento_id' => 'required|exists:tipo_apartamentos,id',
            'fecha_vencimiento'   => 'required|date',
        ], [
            'gasto_mes_id.required'        => 'Debe seleccionar un mes de gastos.',
            'gasto_mes_id.exists'          => 'El mes de gastos no es válido.',
            'tipo_apartamento_id.required' => 'Debe seleccionar un tipo de inmueble.',
            'tipo_apartamento_id.exists'   => 'El tipo de inmueble no es válido.',
            'fecha_vencimiento.required'   => 'La fecha de vencimiento es obligatoria.',
        ]);

        try {
            DB::beginTransaction();

            $gastoMes = GastoMes::findOrFail($request->gasto_mes_id);
            $apartamentos = Apartamento::with('tipo')
                                ->where('tipo_apartamento_id', $request->tipo_apartamento_id)
                                ->get();

            if ($apartamentos->isEmpty()) {
                return redirect()->back()->withInput()->with('error', 'No hay apartamentos registrados bajo este tipo de inmueble.');
            }

            $alicuota = $apartamentos->first()->tipo->alicuota;
            if (is_null($alicuota) || $alicuota <= 0) {
                return redirect()->back()->withInput()->with('error', 'El tipo de apartamento no tiene una alícuota válida configurada mayor a 0.');
            }

            $montoTotal = round($gastoMes->total_gastos * ($alicuota / 100), 2);
            $mesFormateado = Carbon::parse($gastoMes->mes_anio)->translatedFormat('F Y');
            $descripcion = "Mensualidad de Condominio - " . ucfirst($mesFormateado);

            $generadas = 0;
            $omitidas = 0;

            foreach ($apartamentos as $apartamento) {
                // Evitar duplicados
                if (Factura::where('apartamento_id', $apartamento->id)->where('descripcion', $descripcion)->exists()) {
                    $omitidas++;
                    continue;
                }

                $nuevaFactura = Factura::create([
                    'apartamento_id'    => $apartamento->id,
                    'descripcion'       => $descripcion,
                    'monto_total'       => $montoTotal,
                    'saldo_pendiente'   => $montoTotal,
                    'estado'            => 'no_pagado',
                    'fecha_vencimiento' => $request->fecha_vencimiento,
                ]);

                $apartamento->increment('deuda_actual', $montoTotal);
                $generadas++;

                // Enviar Correo Masivo
                if ($apartamento->propietario && $apartamento->propietario->email) {
                    Mail::to($apartamento->propietario->email)->send(new FacturaEmitidaMail($nuevaFactura, $gastoMes));
                }
            }

            if (!$gastoMes->procesado && $generadas > 0) {
                $gastoMes->update(['procesado' => true]);
            }

            DB::commit();

            $mensaje = "Proceso masivo finalizado. Facturas generadas: $generadas.";
            if ($omitidas > 0) {
                $mensaje .= " Omitidas (ya existían): $omitidas.";
            }

            return redirect()->route('facturas.index')->with('exito', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error en la facturación masiva: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el detalle.
     */
    public function show(Factura $factura)
    {
        return redirect()->route('facturas.index');
    }

    /**
     * No habilitaremos edición manual de facturas por ahora, los montos se alteran con pagos.
     */
    public function edit(Factura $factura)
    {
        return redirect()->route('facturas.index');
    }

    /**
     * Eliminar factura si hubo algún error al emitirla (y corregir saldo del apartamento).
     */
    public function destroy(Factura $factura)
    {
        try {
            DB::beginTransaction();

            if ($factura->estado === 'pagado') {
                return redirect()->route('facturas.index')->with('error', 'No se puede eliminar una factura que ya ha sido pagada en su totalidad.');
            }

            if ($factura->monto_total > $factura->saldo_pendiente) {
                return redirect()->route('facturas.index')->with('error', 'La factura tiene pagos parciales o asociados registrados. Consulte la base.');
            }

            // Restar de la deuda del apartamento el saldo_pendiente (que coincide con monto_total si no hay pagos)
            $apartamento = Apartamento::findOrFail($factura->apartamento_id);
            $apartamento->decrement('deuda_actual', $factura->saldo_pendiente);

            $factura->delete();

            DB::commit();

            return redirect()->route('facturas.index')
                ->with('exito', 'Factura anulada correctamente. Deuda del apartamento ajustada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('facturas.index')->with('error', 'Error al borrar factura: ' . $e->getMessage());
        }
    }
}
