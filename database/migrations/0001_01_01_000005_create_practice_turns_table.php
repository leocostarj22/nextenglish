<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_turns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_session_id')->nullable()->constrained('practice_sessions')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('scenario', 120);
            $table->text('user_input');
            $table->longText('ai_original_json')->nullable();
            $table->text('corrected')->nullable();
            $table->text('improved')->nullable();
            $table->text('explanation')->nullable();
            $table->text('pronunciation_tip')->nullable();
            $table->unsignedTinyInteger('score')->default(0);
            $table->string('model', 120)->nullable();
            $table->unsignedInteger('tokens_in')->nullable();
            $table->unsignedInteger('tokens_out')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'scenario']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_turns');
    }
};