<?php

namespace App\Http\Controllers;

use App\Models\TipoApartamento;
use Illuminate\Http\Request;

class TipoApartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tipos_apartamentos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipos_apartamentos.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoApartamento $tipoApartamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoApartamento $tipoApartamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoApartamento $tipoApartamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoApartamento $tipoApartamento)
    {
        //
    }
}
