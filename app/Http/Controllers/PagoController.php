<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Factura;
use App\Models\Apartamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /**
     * Listado histórico de pagos.
     */
    public function index(Request $request)
    {
        $query = Pago::with('apartamento.propietario')->latest();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('referencia', 'LIKE', "%$buscar%")
                  ->orWhere('id', 'LIKE', "%$buscar%")
                  ->orWhereHas('apartamento', function($q) use ($buscar) {
                      $q->where('numero', 'LIKE', "%$buscar%")
                        ->orWhere('torre', 'LIKE', "%$buscar%");
                  });
        }

        $pagos = $query->get();
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Mostrar formulario para registrar un pago.
     */
    public function create()
    {
        // Traer facturas que no estén pagadas totalmente
        $facturas = Factura::with('apartamento.propietario')
            ->whereIn('estado', ['no_pagado', 'pago_parcial'])
            ->orderBy('fecha_vencimiento')
            ->get();

        return view('pagos.crear', compact('facturas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'factura_id'  => 'required|exists:facturas,id',
            'metodo_pago' => 'required|string',
            'referencia'  => 'nullable|string|max:255',
            'monto'       => 'required|numeric|min:0.01',
            'fecha_pago'  => 'required|date|before_or_equal:today',
        ], [
            'factura_id.required'  => 'Debe seleccionar una factura.',
            'factura_id.exists'    => 'La factura seleccionada no existe.',
            'metodo_pago.required' => 'Debe elegir un método de pago.',
            'monto.required'       => 'El monto es obligatorio.',
            'monto.numeric'        => 'El monto debe ser numérico.',
            'monto.min'            => 'El monto debe ser mayor a 0.',
            'fecha_pago.required'  => 'La fecha del pago es obligatoria.',
            'fecha_pago.before_or_equal' => 'La fecha no puede ser futura.',
        ]);

        try {
            DB::beginTransaction();

            $factura = Factura::findOrFail($request->factura_id);
            $apartamento = Apartamento::findOrFail($factura->apartamento_id);

            $montoPago = $request->monto;

            // Crear el Pago (El modelo solo pide apartamento_id pero lógicamente pertenece a la factura por concepto.
            // La entidad Pago original solo tiene apartamento_id, así que guardamos lo que está definido)
            // Se asume que en "Pagos" se lleva control por apartamento y se abona a la factura.
            $pago = Pago::create([
                'apartamento_id' => $apartamento->id,
                'monto'          => $montoPago,
                'fecha_pago'     => $request->fecha_pago,
                'referencia'     => $request->referencia,
                'metodo_pago'    => $request->metodo_pago,
            ]);

            // Actualizar saldo de la Factura (No pasa de 0)
            $nuevoSaldoFactura = max(0, $factura->saldo_pendiente - $montoPago);
            $estadoFactura = $nuevoSaldoFactura <= 0 ? 'pagado' : 'pago_parcial';

            $factura->update([
                'saldo_pendiente' => $nuevoSaldoFactura,
                'estado'          => $estadoFactura,
            ]);

            // Descontar la deuda total del apartamento (Si sobra, queda en negativo como Saldo a Favor)
            $apartamento->decrement('deuda_actual', $montoPago);

            DB::commit();

            return redirect()->route('pagos.index')->with('exito', 'Pago registrado correctamente. La deuda del apartamento y la factura han sido actualizados.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al registrar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Listado de Apartamentos con Deuda Pendiente.
     */
    public function deudores(Request $request)
    {
        $query = Apartamento::with('propietario')->where('deuda_actual', '>', 0);
        
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('propietario', function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%$buscar%")
                  ->orWhere('apellido', 'LIKE', "%$buscar%")
                  ->orWhere('cedula', 'LIKE', "%$buscar%");
            })->orWhere('numero', 'LIKE', "%$buscar%");
        }

        $deudores = $query->orderBy('deuda_actual', 'desc')->get();
        return view('pagos.deudores', compact('deudores'));
    }

    /**
     * Procesar un abono inteligente desde el Panel de Deudores
     */
    public function abonarDeuda(Request $request)
    {
        $request->validate([
            'apartamento_id' => 'required|exists:apartamentos,id',
            'monto'          => 'required|numeric|min:0.01',
            'metodo_pago'    => 'required|string',
            'referencia'     => 'nullable|string|max:255',
            'fecha_pago'     => 'required|date|before_or_equal:today',
        ], [
            'monto.required' => 'Debe proveer un monto de abono.',
        ]);

        try {
            DB::beginTransaction();

            $apartamento = Apartamento::findOrFail($request->apartamento_id);
            $montoAbono = $request->monto;

            // 1. Crear el registro general de pago asociado al apartamento
            Pago::create([
                'apartamento_id' => $apartamento->id,
                'monto'          => $montoAbono,
                'fecha_pago'     => $request->fecha_pago,
                'referencia'     => $request->referencia,
                'metodo_pago'    => $request->metodo_pago,
            ]);

            // 2. Descontar la deuda actual global del apartamento
            $apartamento->decrement('deuda_actual', $montoAbono);

            // 3. Obtener facturas pendientes (más antiguas primero) para saldarlas en cascada
            $facturasPendientes = Factura::where('apartamento_id', $apartamento->id)
                                         ->whereIn('estado', ['no_pagado', 'pago_parcial'])
                                         ->orderBy('fecha_vencimiento', 'asc')
                                         ->get();
            
            $montoRestante = $montoAbono;
            foreach ($facturasPendientes as $factura) {
                if ($montoRestante <= 0) break;

                if ($montoRestante >= $factura->saldo_pendiente) {
                    $montoRestante -= $factura->saldo_pendiente;
                    $factura->update([
                        'saldo_pendiente' => 0,
                        'estado'          => 'pagado'
                    ]);
                } else {
                    $factura->update([
                        'saldo_pendiente' => $factura->saldo_pendiente - $montoRestante,
                        'estado'          => 'pago_parcial'
                    ]);
                    $montoRestante = 0;
                }
            }

            DB::commit();
            return redirect()->back()->with('exito', 'Abono registrado con éxito. El pago fue debitado de la deuda total y aplicado a sus facturas pendientes en orden de antigüedad.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al procesar el abono: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        return redirect()->route('pagos.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        return redirect()->route('pagos.index')->with('error', 'La edición de pagos no está permitida. Consulte a soporte.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        // No implementado
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        return redirect()->route('pagos.index')->with('error', 'La eliminación de pagos requiere reversión completa y no está habilitada en esta versión.');
    }
}
