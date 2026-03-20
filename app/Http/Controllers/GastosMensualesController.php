<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GastosMensualesController extends Controller
{
    //
    public function index()
    {
        return view('gastos_mensuales.index');
    }

    public function create()
    {
        return view('gastos_mensuales.crear');
    }

    public function edit($id)
    {
        return view('gastos_mensuales.editar');
    }
}
