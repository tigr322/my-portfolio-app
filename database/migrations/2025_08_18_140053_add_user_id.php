<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Шаг 1: добавить столбец, но пока nullable и без FK
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('status');
        });

       
     
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
        
        });
    }
};
