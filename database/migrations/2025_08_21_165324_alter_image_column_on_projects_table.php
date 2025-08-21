<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // требуется doctrine/dbal для change()
            $table->string('image', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // если нужно откатить, верни к исходному типу (пример: integer nullable)
            $table->integer('image')->nullable()->change();
        });
    }
};
