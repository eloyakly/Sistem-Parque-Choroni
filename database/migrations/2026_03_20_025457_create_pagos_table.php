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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartamento_id')->constrained('apartamentos');
            $table->decimal('monto', 15, 2);
            $table->date('fecha_pago');
            $table->string('referencia')->nullable(); // Número de transacción
            $table->string('metodo_pago'); // Ej: Transferencia, Efectivo, Zelle
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
