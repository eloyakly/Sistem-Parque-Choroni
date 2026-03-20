<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('gasto_mes', function (Blueprint $table) {
            $table->id();
            $table->string('mes_anio'); // Ej: "03-2026"
            $table->decimal('total_gastos', 15, 2)->default(0);
            $table->boolean('procesado')->default(false); // Para saber si ya se generaron las facturas
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasto_mes');
    }
};
