<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    //
    protected $fillable = ['apartamento_id', 'monto', 'fecha_pago', 'referencia', 'metodo_pago'];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto'      => 'decimal:2',
    ];

    public function apartamento() {
        return $this->belongsTo(Apartamento::class);
    }
}
