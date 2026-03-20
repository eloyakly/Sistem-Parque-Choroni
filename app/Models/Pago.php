<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    //
    protected $fillable = ['apartamento_id', 'monto', 'fecha_pago', 'referencia', 'metodo_pago'];

    public function apartamento() {
        return $this->belongsTo(Apartamento::class);
    }
}
