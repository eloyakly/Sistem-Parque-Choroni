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
        Schema::create('apartamentos', function (Blueprint $table) {
            $table->id();
            $table->string('torre');          // Torre / Bloque al que pertenece
            $table->string('numero');         // Ej: A-101
            $table->foreignId('tipo_apartamento_id')->constrained('tipo_apartamentos')->onDelete('restrict');
            $table->foreignId('propietario_id')->constrained('propietarios')->onDelete('restrict');
            $table->decimal('deuda_actual', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartamentos');
    }
};
