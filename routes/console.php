<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Enviar correos pendientes automáticamente apenas inicia el nuevo día (cupos frescos)
Schedule::command('correos:enviar-pendientes')->dailyAt('00:01');

// Reintento cada hora durante el día por si el sistema se encendió tarde o quedaron pendientes
Schedule::command('correos:enviar-pendientes')->hourly();
