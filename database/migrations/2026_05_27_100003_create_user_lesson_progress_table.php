<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('not_started'); // not_started | in_progress | completed
            $table->unsignedSmallInteger('xp_earned')->default(0);
            $table->unsignedTinyInteger('correct_answers')->default(0);
            $table->unsignedTinyInteger('total_answers')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_lesson_progress');
    }
};
