<?php

namespace App\Http\Controllers;

use App\Models\TipoApartamento;
use Illuminate\Http\Request;

class TipoApartamentoController extends Controller
{
    /**
     * Lista todos los tipos de inmueble con filtro de búsqueda opcional.
     */
    public function index(Request $request)
    {
        $query = TipoApartamento::query();
        
        if ($request->filled('buscar')) {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }

        $tipos = $query->orderBy('nombre')->get();
        return view('tipos_apartamentos.index', compact('tipos'));
    }

    /**
     * Muestra el formulario para crear un tipo.
     */
    public function create()
    {
        return view('tipos_apartamentos.crear');
    }

    /**
     * Guarda un nuevo tipo de inmueble.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'    => 'required|string|unique:tipo_apartamentos,nombre',
            'alicuota'  => 'required|numeric|min:0|max:100', // Permite todos los decimales que vengan en el request
        ], [
            'nombre.required'   => 'Debe ingresar un nombre de inmueble válido.',
            'nombre.unique'     => 'Ese tipo de inmueble ya se encuentra registrado.',
            'alicuota.required' => 'La alícuota es obligatoria.',
            'alicuota.numeric'  => 'La alícuota debe ser un número válido.',
            'alicuota.min'      => 'La alícuota no puede ser negativa.',
            'alicuota.max'      => 'La alícuota no puede exceder el 100%.',
        ]);

        TipoApartamento::create($datos);

        return redirect()->route('tipos-apartamentos.index')
            ->with('exito', 'Tipo de inmueble registrado correctamente.');
    }

    /**
     * Muestra el detalle (no aplica).
     */
    public function show(TipoApartamento $tiposApartamento)
    {
        return redirect()->route('tipos-apartamentos.index');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(TipoApartamento $tiposApartamento)
    {
        return view('tipos_apartamentos.editar', ['tipo' => $tiposApartamento]);
    }

    /**
     * Actualiza un tipo de inmueble.
     */
    public function update(Request $request, TipoApartamento $tiposApartamento)
    {
        $datos = $request->validate([
            'nombre'   => 'required|string|unique:tipo_apartamentos,nombre,' . $tiposApartamento->id,
            'alicuota' => 'required|numeric|min:0|max:100',
        ], [
            'nombre.required'   => 'Debe ingresar un nombre de inmueble válido.',
            'nombre.unique'     => 'Ese tipo de inmueble ya se encuentra registrado.',
            'alicuota.required' => 'La alícuota es obligatoria.',
            'alicuota.numeric'  => 'La alícuota debe ser un número válido.',
            'alicuota.min'      => 'La alícuota no puede ser negativa.',
            'alicuota.max'      => 'La alícuota no puede exceder el 100%.',
        ]);

        $tiposApartamento->update($datos);

        return redirect()->route('tipos-apartamentos.index')
            ->with('exito', 'Tipo de inmueble actualizado correctamente.');
    }

    /**
     * Elimina un tipo de inmueble.
     */
    public function destroy(TipoApartamento $tiposApartamento)
    {
        if ($tiposApartamento->apartamentos()->count() > 0) {
            return redirect()->route('tipos-apartamentos.index')
                ->with('error', 'No se puede eliminar: hay apartamentos asignados a este tipo.');
        }

        $tiposApartamento->delete();

        return redirect()->route('tipos-apartamentos.index')
            ->with('exito', 'Tipo de inmueble eliminado correctamente.');
    }
}
