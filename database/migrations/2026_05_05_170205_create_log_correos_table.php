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
        Schema::create('log_correos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');                   // 'factura', 'recibo_pago'
            $table->string('destinatario');           // email
            $table->string('asunto')->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable(); // factura_id / pago_id
            $table->enum('estado', ['enviado', 'fallido', 'omitido'])->default('enviado');
            $table->text('error')->nullable();        // mensaje de error si falla
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_correos');
    }
};
