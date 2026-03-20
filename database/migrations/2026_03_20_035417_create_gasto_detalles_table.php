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
        Schema::create('gasto_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gasto_mes_id')->constrained('gasto_mes')->onDelete('cascade');
            $table->string('descripcion'); // Nombre del gasto
            $table->decimal('monto', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasto_detalles');
    }
};
