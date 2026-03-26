<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TipoApartamentoController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\ApartamentoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\GastosMensualesController;

Route::get('/', function () {
    return view('acceso');
});

Route::get('/acceso', function () {
    return view('acceso');
})->name('login');

Route::post('/login', [InicioController::class, 'login'])->name('login.post');
Route::post('/logout', [InicioController::class, 'logout'])->name('logout');

Route::middleware([\App\Http\Middleware\CheckSession::class])->group(function () {
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');

    Route::resource('/gastos-mensuales', GastosMensualesController::class);

    // Rutas de Recursos
    Route::resource('/tipos-apartamentos', TipoApartamentoController::class);
    Route::resource('/propietarios', PropietarioController::class);
    Route::resource('/apartamentos', ApartamentoController::class);

    Route::post('/facturas/masiva', [FacturaController::class, 'storeMasivo'])->name('facturas.masiva');
    Route::resource('/facturas', FacturaController::class);

    Route::get('/deudores', [PagoController::class, 'deudores'])->name('pagos.deudores');
    Route::post('/pagos/abonar-deuda', [PagoController::class, 'abonarDeuda'])->name('pagos.abonar');
    Route::resource('/pagos', PagoController::class);
});