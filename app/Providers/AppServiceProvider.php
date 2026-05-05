<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Http\View\Composers\EstadoCorreosComposer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('es');
        Paginator::useBootstrapFive();

        // Inyectar estado de correos en la barra superior de todas las páginas
        View::composer('components.navegacion_superior', EstadoCorreosComposer::class);
    }
}
