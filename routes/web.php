<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TipoApartamentoController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\ApartamentoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PagoController;


use App\Http\Controllers\InicioController;

Route::get('/', function () {
    return view('acceso');
});

Route::get('/acceso', function () {
    return view('acceso');
})->name('login');

Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');

use App\Http\Controllers\GastosMensualesController;
Route::resource('/gastos-mensuales', GastosMensualesController::class);

// Rutas de Recursos
Route::resource('/tipos-apartamentos', TipoApartamentoController::class);
Route::resource('/propietarios', PropietarioController::class);
Route::resource('/apartamentos', ApartamentoController::class);
Route::resource('/facturas', FacturaController::class);
Route::resource('/pagos', PagoController::class);