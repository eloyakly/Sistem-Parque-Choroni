<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    //
    protected $fillable = ['apartamento_id', 'descripcion', 'monto_total', 'saldo_pendiente', 'estado', 'fecha_vencimiento'];

    // Scope para filtrar solo las que deben dinero
    public function scopePendientes($query) {
        return $query->whereIn('estado', ['no_pagado', 'pago_parcial']);
    }
}
