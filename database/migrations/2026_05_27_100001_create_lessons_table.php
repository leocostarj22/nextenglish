<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('lesson_modules')->cascadeOnDelete();
            $table->unsignedTinyInteger('order');
            $table->string('title');
            $table->text('objective');
            $table->string('grammar_point')->nullable();
            $table->text('intro_text');
            $table->json('vocabulary');
            $table->json('examples');
            $table->json('tips')->nullable();
            $table->unsignedSmallInteger('xp_reward')->default(50);
            $table->timestamps();

            $table->index(['module_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
