<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    //
    protected $fillable = ['nombre', 'apellido', 'cedula', 'telefono', 'email'];

    public function apartamentos() {
        return $this->hasMany(Apartamento::class);
    }
}
