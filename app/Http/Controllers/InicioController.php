<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Apartamento;
use App\Models\Propietario;
use App\Models\Factura;
use App\Models\Pago;

class InicioController extends Controller
{
    //
    public function login(Request $request)
    {
        $email = $request->email;
        $clave = $request->clave;
        $user = User::where('email', $email)->where('password', $clave)->first();
        if ($user) {
            session(['user_id' => $user->id]);
            return redirect('/inicio');
        } else {
            return redirect('/acceso')->with('error', 'Usuario o contraseña incorrectos');
        }
    }

    public function logout()
    {
        session()->forget('user_id');
        return redirect('/acceso')->with('success', 'Sesión cerrada correctamente');
    }

    public function index()
    {
        $totalApartamentos = Apartamento::count();
        $totalPropietarios = Propietario::count();
        $facturasPendientes = Factura::where('estado', 'Pendiente')->count();
        $pagosMes = Pago::whereMonth('fecha_pago', date('m'))->whereYear('fecha_pago', date('Y'))->count();

        return view('inicio.index', compact('totalApartamentos', 'totalPropietarios', 'facturasPendientes', 'pagosMes'));
    }
}
