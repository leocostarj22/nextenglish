<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_modules', function (Blueprint $table) {
            $table->id();
            $table->string('cefr_level', 2);
            $table->unsignedTinyInteger('order');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('min_xp_to_unlock')->default(0);
            $table->timestamps();

            $table->index('cefr_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_modules');
    }
};
