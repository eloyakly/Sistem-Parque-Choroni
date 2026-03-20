<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoMes extends Model
{
    //
    public function detalles()
    {
        return $this->hasMany(GastoDetalle::class);
    }
}
