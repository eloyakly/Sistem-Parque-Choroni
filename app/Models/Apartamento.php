<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{
    //
    protected $fillable = ['numero', 'tipo_apartamento_id', 'propietario_id', 'deuda_actual'];

    public function tipo() {
        return $this->belongsTo(TipoApartamento::class, 'tipo_apartamento_id');
    }

    public function propietario() {
        return $this->belongsTo(Propietario::class);
    }

    public function facturas() {
        return $this->hasMany(Factura::class);
}
}
