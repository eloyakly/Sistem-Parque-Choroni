<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoDetalle extends Model
{
    protected $table = 'gasto_detalles';

    protected $fillable = ['gasto_mes_id', 'descripcion', 'monto'];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function gastoMes()
    {
        return $this->belongsTo(GastoMes::class);
    }
}
