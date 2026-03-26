<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('user_id')) {
            return redirect('/acceso')->with('error', 'Debe iniciar sesión para acceder al sistema.');
        }

        // Compartir el usuario actual en todas las vistas dentro del middleware
        $user = \App\Models\User::find(session('user_id'));
        if (!$user) {
            session()->forget('user_id');
            return redirect('/acceso')->with('error', 'Sesión inválida.');
        }
        
        view()->share('user', $user);

        return $next($request);
    }
}
