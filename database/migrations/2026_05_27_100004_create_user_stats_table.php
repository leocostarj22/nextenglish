<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_xp')->default(0);
            $table->unsignedSmallInteger('current_streak')->default(0);
            $table->unsignedSmallInteger('longest_streak')->default(0);
            $table->date('last_activity_date')->nullable();
            $table->string('current_cefr_level', 2)->default('A1');
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_stats');
    }
};
