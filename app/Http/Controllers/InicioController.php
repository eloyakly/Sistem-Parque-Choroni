<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class InicioController extends Controller
{
    //
    public function index(Request $request)
    {
        $email= $request->email;
        $clave= $request->clave;
        $user=User::where('email', $email)->where('password', $clave)->first();
        if($user){
            return view('inicio.index', compact('user'));
        }else{
            return view('acceso')->with('error', 'Usuario o contraseña incorrectos');
        }
    }
}
