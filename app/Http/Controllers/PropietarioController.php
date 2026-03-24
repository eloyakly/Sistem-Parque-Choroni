<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use Illuminate\Http\Request;

class PropietarioController extends Controller
{
    /**
     * Lista todos los propietarios.
     */
    public function index(Request $request)
    {
        $query = Propietario::with('apartamentos');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('nombre', 'LIKE', "%$buscar%")
                  ->orWhere('apellido', 'LIKE', "%$buscar%")
                  ->orWhere('cedula', 'LIKE', "%$buscar%");
        }

        $propietarios = $query->orderBy('apellido')->get();
        return view('propietarios.index', compact('propietarios'));
    }

    /**
     * Muestra el formulario para crear un propietario.
     */
    public function create()
    {
        return view('propietarios.crear');
    }

    /**
     * Guarda un nuevo propietario en la base de datos.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula'   => 'required|string|max:20|unique:propietarios,cedula',
            'telefono' => 'nullable|string|max:20',
            'email'    => 'required|email|unique:propietarios,email',
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'cedula.required'   => 'La cédula es obligatoria.',
            'cedula.unique'     => 'Ya existe un propietario con esa cédula.',
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'El correo no tiene formato válido.',
            'email.unique'      => 'Ya existe un propietario con ese correo.',
        ]);

        Propietario::create($datos);

        return redirect()->route('propietarios.index')
            ->with('exito', 'Propietario registrado correctamente.');
    }

    /**
     * Muestra el detalle de un propietario.
     */
    public function show(Propietario $propietario)
    {
        $propietario->load('apartamentos.tipo');
        return view('propietarios.ver', compact('propietario'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Propietario $propietario)
    {
        return view('propietarios.editar', compact('propietario'));
    }

    /**
     * Actualiza los datos del propietario.
     */
    public function update(Request $request, Propietario $propietario)
    {
        $datos = $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula'   => 'required|string|max:20|unique:propietarios,cedula,' . $propietario->id,
            'telefono' => 'nullable|string|max:20',
            'email'    => 'required|email|unique:propietarios,email,' . $propietario->id,
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'cedula.required'   => 'La cédula es obligatoria.',
            'cedula.unique'     => 'Ya existe un propietario con esa cédula.',
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'El correo no tiene formato válido.',
            'email.unique'      => 'Ya existe un propietario con ese correo.',
        ]);

        $propietario->update($datos);

        return redirect()->route('propietarios.index')
            ->with('exito', 'Propietario actualizado correctamente.');
    }

    /**
     * Elimina un propietario.
     */
    public function destroy(Propietario $propietario)
    {
        if ($propietario->apartamentos()->count() > 0) {
            return redirect()->route('propietarios.index')
                ->with('error', 'No se puede eliminar: el propietario tiene apartamentos asignados.');
        }

        $propietario->delete();

        return redirect()->route('propietarios.index')
            ->with('exito', 'Propietario eliminado correctamente.');
    }
}
