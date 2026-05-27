<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('order');
            $table->string('type', 20); // mcq | order_sentence | fill_blank | free_write
            $table->text('prompt');
            $table->json('options')->nullable();
            $table->text('correct_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->unsignedSmallInteger('xp_reward')->default(10);
            $table->timestamps();

            $table->index(['lesson_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
