<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN, so we use a raw approach
        // First add the datos_extra column
        Schema::table('log_correos', function (Blueprint $table) {
            $table->text('datos_extra')->nullable()->after('error');
        });

        // Alter the enum to include 'pendiente' — MySQL approach
        DB::statement("ALTER TABLE log_correos MODIFY COLUMN estado ENUM('enviado','fallido','omitido','pendiente') NOT NULL DEFAULT 'enviado'");
    }

    public function down(): void
    {
        Schema::table('log_correos', function (Blueprint $table) {
            $table->dropColumn('datos_extra');
        });

        DB::statement("ALTER TABLE log_correos MODIFY COLUMN estado ENUM('enviado','fallido','omitido') NOT NULL DEFAULT 'enviado'");
    }
};
