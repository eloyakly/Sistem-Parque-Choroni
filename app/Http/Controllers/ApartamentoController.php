<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Propietario;
use App\Models\TipoApartamento;
use Illuminate\Http\Request;

class ApartamentoController extends Controller
{
    /**
     * Lista todos los apartamentos con opción a filtrado.
     */
    public function index(Request $request)
    {
        $query = Apartamento::with(['tipo', 'propietario']);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('numero', 'LIKE', "%$buscar%")
                  ->orWhere('torre', 'LIKE', "%$buscar%")
                  ->orWhereHas('propietario', function($q) use ($buscar) {
                      $q->where('nombre', 'LIKE', "%$buscar%")
                        ->orWhere('apellido', 'LIKE', "%$buscar%")
                        ->orWhere('cedula', 'LIKE', "%$buscar%");
                  });
        }

        $apartamentos = $query->get();
        return view('apartamentos.index', compact('apartamentos'));
    }

    /**
     * Muestra el formulario para crear un apartamento.
     */
    public function create()
    {
        $propietarios = Propietario::orderBy('apellido')->get();
        $tipos        = TipoApartamento::orderBy('nombre')->get();
        return view('apartamentos.crear', compact('propietarios', 'tipos'));
    }

    /**
     * Guarda un nuevo apartamento en la base de datos.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'torre'                => 'required|string|in:Torre 1,Torre 2,Torre 3,Torre 4',
            'numero'               => 'required|string|max:20',
            'tipo_apartamento_id'  => 'required|exists:tipo_apartamentos,id',
            'propietario_id'       => 'required|exists:propietarios,id',
            'deuda_actual'         => 'nullable|numeric|min:0',
        ], [
            'torre.required'               => 'Debe seleccionar una torre o bloque.',
            'torre.in'                     => 'La torre seleccionada no es válida.',
            'numero.required'              => 'El número del inmueble es obligatorio.',
            'tipo_apartamento_id.required' => 'Debe seleccionar un tipo de inmueble.',
            'tipo_apartamento_id.exists'   => 'El tipo de inmueble seleccionado no es válido.',
            'propietario_id.required'      => 'Debe seleccionar un propietario.',
            'propietario_id.exists'        => 'El propietario seleccionado no es válido.',
            'deuda_actual.numeric'         => 'La deuda debe ser un número.',
            'deuda_actual.min'             => 'La deuda no puede ser negativa.',
        ]);

        $datos['deuda_actual'] = $datos['deuda_actual'] ?? 0;

        Apartamento::create($datos);

        return redirect()->route('apartamentos.index')
            ->with('exito', 'Apartamento registrado correctamente.');
    }

    /**
     * Muestra el detalle de un apartamento.
     */
    public function show(Apartamento $apartamento)
    {
        $apartamento->load(['tipo', 'propietario']);
        return view('apartamentos.ver', compact('apartamento'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Apartamento $apartamento)
    {
        $propietarios = Propietario::orderBy('apellido')->get();
        $tipos        = TipoApartamento::orderBy('nombre')->get();
        return view('apartamentos.editar', compact('apartamento', 'propietarios', 'tipos'));
    }

    /**
     * Actualiza los datos de un apartamento.
     */
    public function update(Request $request, Apartamento $apartamento)
    {
        $datos = $request->validate([
            'torre'                => 'required|string|in:Torre 1,Torre 2,Torre 3,Torre 4',
            'numero'               => 'required|string|max:20',
            'tipo_apartamento_id'  => 'required|exists:tipo_apartamentos,id',
            'propietario_id'       => 'required|exists:propietarios,id',
            'deuda_actual'         => 'nullable|numeric|min:0',
        ], [
            'torre.required'               => 'Debe seleccionar una torre o bloque.',
            'torre.in'                     => 'La torre seleccionada no es válida.',
            'numero.required'              => 'El número del inmueble es obligatorio.',
            'tipo_apartamento_id.required' => 'Debe seleccionar un tipo de inmueble.',
            'tipo_apartamento_id.exists'   => 'El tipo de inmueble seleccionado no es válido.',
            'propietario_id.required'      => 'Debe seleccionar un propietario.',
            'propietario_id.exists'        => 'El propietario seleccionado no es válido.',
            'deuda_actual.numeric'         => 'La deuda debe ser un número.',
            'deuda_actual.min'             => 'La deuda no puede ser negativa.',
        ]);

        $datos['deuda_actual'] = $datos['deuda_actual'] ?? 0;

        $apartamento->update($datos);

        return redirect()->route('apartamentos.index')
            ->with('exito', 'Apartamento actualizado correctamente.');
    }

    /**
     * Elimina un apartamento.
     */
    public function destroy(Apartamento $apartamento)
    {
        try {
            if ($apartamento->facturas()->count() > 0 || $apartamento->pagos()->count() > 0) {
                return redirect()->route('apartamentos.index')
                    ->with('error', 'No se puede eliminar el inmueble de la ' . $apartamento->torre . ' porque tiene recibos o pagos asociados.');
            }

            $apartamento->delete();

            return redirect()->route('apartamentos.index')
                ->with('exito', 'Apartamento eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('apartamentos.index')
                ->with('error', 'Ocurrió un error al eliminar el apartamento. Asegúrese de que no tenga registros financieros asociados.');
        }
    }
}
