<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestMassiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Tipo de Apartamento
        $tipo = \App\Models\TipoApartamento::create([
            'nombre' => 'Apartamento Tipo A (Prueba)',
            'alicuota' => 0.40, // 0.40% cada uno (250 * 0.40 = 100%)
        ]);

        // 2. Crear un Gasto del Mes
        $gastoMes = \App\Models\GastoMes::create([
            'mes_anio' => \Carbon\Carbon::now()->format('Y-m'),
            'total_gastos' => 1000.00,
            'procesado' => false,
        ]);

        // 3. Crear 250 Propietarios y Apartamentos
        for ($i = 1; $i <= 250; $i++) {
            $propietario = \App\Models\Propietario::create([
                'nombre' => 'Propietario Prueba',
                'apellido' => 'Numero ' . $i,
                'cedula' => 'V-TEST-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'telefono' => '0414-0000000',
                'email' => 'propietario.prueba.' . $i . '@ejemplo.com',
            ]);

            \App\Models\Apartamento::create([
                'torre' => 'Torre Prueba',
                'numero' => 'A-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tipo_apartamento_id' => $tipo->id,
                'propietario_id' => $propietario->id,
                'deuda_actual' => 0,
            ]);
        }
    }
}
