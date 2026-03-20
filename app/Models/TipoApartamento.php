<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoApartamento extends Model
{
    //
    protected $fillable = ['nombre', 'alicuota'];

public function apartamentos() {
    return $this->hasMany(Apartamento::class);
}
}
