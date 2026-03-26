<?php

namespace App\Http\Controllers;

use App\Models\GastoMes;
use App\Models\GastoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GastosMensualesController extends Controller
{
    /**
     * Listado de los gastos mensuales registrados.
     */
    public function index(Request $request)
    {
        $query = GastoMes::with('detalles')->latest();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('mes_anio', 'LIKE', "%$buscar%")
                  ->orWhereHas('detalles', function($q) use ($buscar) {
                      $q->where('descripcion', 'LIKE', "%$buscar%");
                  });
        }

        $gastosMes = $query->get();
        return view('gastos_mensuales.index', compact('gastosMes'));
    }

    /**
     * Mostrar formulario para registrar los gastos de un mes.
     */
    public function create()
    {
        return view('gastos_mensuales.crear');
    }

    /**
     * Guardar el gasto mensual con sus múltiples detalles.
     */
    public function store(Request $request)
    {
        // Validar que mes_anio y los arreglos existan y tengan datos coherentes
        $request->validate([
            'mes_anio'      => 'required|date_format:Y-m',
            'descripcion'   => 'required|array|min:1',
            'descripcion.*' => 'required|string|max:255',
            'monto'         => 'required|array|min:1',
            'monto.*'       => 'required|numeric',
        ], [
            'mes_anio.required'      => 'Debe seleccionar el mes correspondiente.',
            'mes_anio.date_format'   => 'El formato del mes no es válido.',
            'descripcion.required'   => 'Debe registrar al menos un tipo de gasto.',
            'descripcion.min'        => 'Debe registrar al menos un tipo de gasto.',
            'descripcion.*.required' => 'La descripción del concepto no puede estar vacía.',
            'monto.required'         => 'Hay conceptos sin monto cargado.',
            'monto.*.required'       => 'El monto es obligatorio para cada concepto.',
            'monto.*.numeric'        => 'El monto debe ser un valor numérico.',
        ]);

        // Asegurar que no se repita un registro de gastos para el mismo mes
        if (GastoMes::where('mes_anio', $request->mes_anio)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Ya se han registrado los gastos base para el mes indicado. Edite el registro existente si necesita hacer cambios.');
        }

        try {
            DB::beginTransaction();

            $descripciones = $request->descripcion;
            $montos = $request->monto;

            // Calcular el total manual o usar el sum() del array montos (siempre que los índices coincidan)
            $totalGastos = array_sum($montos);

            // Crear el encabezado del mes
            $gastoMes = GastoMes::create([
                'mes_anio'     => $request->mes_anio,
                'total_gastos' => $totalGastos,
                'procesado'    => false,
            ]);

            // Guardar detalles iterando sobre los arreglos
            foreach ($descripciones as $indice => $descripcion) {
                GastoDetalle::create([
                    'gasto_mes_id' => $gastoMes->id,
                    'descripcion'  => $descripcion,
                    'monto'        => $montos[$indice],
                ]);
            }

            DB::commit();

            return redirect()->route('gastos-mensuales.index')->with('exito', 'Gastos del mes registrados satisfactoriamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al guardar los gastos: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el detalle del gasto de un mes (Redirige al index o puede mostrar modal en el futuro).
     */
    public function show($id)
    {
        return redirect()->route('gastos-mensuales.index');
    }

    /**
     * Mostrar formulario para editar un mes registrado (Siempre y cuando no haya sido procesado).
     */
    public function edit($id)
    {
        $gastoMes = GastoMes::with('detalles')->findOrFail($id);

        if ($gastoMes->procesado) {
            return redirect()->route('gastos-mensuales.index')->with('error', 'No se pueden editar los gastos de un mes porque ya han sido procesados y facturados a los apartamentos.');
        }

        return view('gastos_mensuales.editar', compact('gastoMes'));
    }

    /**
     * Actualiza la información y detalles del gasto mensual.
     */
    public function update(Request $request, $id)
    {
        $gastoMes = GastoMes::findOrFail($id);

        if ($gastoMes->procesado) {
            return redirect()->route('gastos-mensuales.index')->with('error', 'No se pueden actualizar gastos ya facturados.');
        }

        $request->validate([
            'mes_anio'      => 'required|date_format:Y-m',
            'descripcion'   => 'required|array|min:1',
            'descripcion.*' => 'required|string|max:255',
            'monto'         => 'required|array|min:1',
            'monto.*'       => 'required|numeric',
        ], [
            'mes_anio.required'      => 'Debe seleccionar el mes correspondiente.',
            'mes_anio.date_format'   => 'El formato del mes no es válido.',
            'descripcion.required'   => 'Debe registrar al menos un tipo de gasto.',
            'descripcion.*.required' => 'La descripción del concepto no puede estar vacía.',
            'monto.*.required'       => 'El monto es obligatorio para cada concepto.',
        ]);

        // Verificar unicidad ignorando el ID actual
        if (GastoMes::where('mes_anio', $request->mes_anio)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Ya existe un registro distinto con ese mismo mes y año.');
        }

        try {
            DB::beginTransaction();

            $descripciones = $request->descripcion;
            $montos = $request->monto;
            $totalGastos = array_sum($montos);

            // Actualizar tabla padre
            $gastoMes->update([
                'mes_anio'     => $request->mes_anio,
                'total_gastos' => $totalGastos,
            ]);

            // Para los detalles, borramos recreamos (Estrategia sencilla para manejar eliminaciones masivas e inserts)
            $gastoMes->detalles()->delete();

            foreach ($descripciones as $indice => $descripcion) {
                GastoDetalle::create([
                    'gasto_mes_id' => $gastoMes->id,
                    'descripcion'  => $descripcion,
                    'monto'        => $montos[$indice],
                ]);
            }

            DB::commit();

            return redirect()->route('gastos-mensuales.index')->with('exito', 'Gastos del mes actualizados satisfactoriamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar los gastos: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar registro completo (Eliminación en cascada de los detalles configurada en BD).
     */
    public function destroy($id)
    {
        $gastoMes = GastoMes::findOrFail($id);

        if ($gastoMes->procesado) {
            return redirect()->route('gastos-mensuales.index')->with('error', 'No se pueden eliminar los gastos de un mes porque ya han sido procesados y facturados a los apartamentos.');
        }

        $gastoMes->delete();

        return redirect()->route('gastos-mensuales.index')->with('exito', 'Registro de gastos eliminado exitosamente.');
    }
}
