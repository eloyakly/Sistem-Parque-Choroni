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

    Route::post('/recibos/masiva', [FacturaController::class, 'storeMasivo'])->name('recibos.masiva');
    Route::resource('/recibos', FacturaController::class)->names('recibos');

    Route::get('/deudores/imprimir', [PagoController::class, 'imprimirDeudores'])->name('pagos.deudores.imprimir');
    Route::get('/deudores', [PagoController::class, 'deudores'])->name('pagos.deudores');
    Route::post('/pagos/abonar-deuda', [PagoController::class, 'abonarDeuda'])->name('pagos.abonar');
    Route::get('/pagos/{pago}/recibo', [PagoController::class, 'descargarRecibo'])->name('pagos.recibo');
    Route::post('/pagos/{pago}/enviar-recibo', [PagoController::class, 'enviarRecibo'])->name('pagos.enviar_recibo');
    
    // Ingresos y Reportes
    Route::get('/pagos/ingresos/imprimir', [PagoController::class, 'imprimirIngresos'])->name('pagos.ingresos.imprimir');
    Route::get('/pagos/reporte-ingresos', [PagoController::class, 'reporteIngresos'])->name('pagos.reporte');
    Route::get('/pagos/ingresos/{mes}', [PagoController::class, 'ingresosPorMes'])->name('pagos.ingresos.mes');
    
    Route::resource('/pagos', PagoController::class);

    // Rutas de Estado de Cuenta
    Route::get('/estado-cuenta/imprimir', [\App\Http\Controllers\EstadoCuentaController::class, 'imprimir'])->name('estado_cuenta.imprimir');
    Route::get('/estado-cuenta', [\App\Http\Controllers\EstadoCuentaController::class, 'index'])->name('estado_cuenta.index');
});