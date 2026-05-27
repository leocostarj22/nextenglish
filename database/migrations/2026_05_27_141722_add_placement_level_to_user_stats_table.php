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
        Schema::table('user_stats', function (Blueprint $table) {
            $table->string('placement_level', 2)->nullable()->after('current_cefr_level');
        });
    }

    public function down(): void
    {
        Schema::table('user_stats', function (Blueprint $table) {
            $table->dropColumn('placement_level');
        });
    }
};
