<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoMes extends Model
{
    protected $table = 'gasto_mes';

    protected $fillable = ['mes_anio', 'total_gastos', 'procesado'];

    protected $casts = [
        'procesado'    => 'boolean',
        'total_gastos' => 'decimal:2',
    ];

    public function detalles()
    {
        return $this->hasMany(GastoDetalle::class);
    }
}
