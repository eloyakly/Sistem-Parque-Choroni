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
       Schema::create('tipo_apartamentos', function (Blueprint $table) {
    $table->id(); // Crea un BigInteger Unsigned
    $table->string('nombre');
    $table->decimal('alicuota', 8, 4);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_apartamentos');
    }
};
