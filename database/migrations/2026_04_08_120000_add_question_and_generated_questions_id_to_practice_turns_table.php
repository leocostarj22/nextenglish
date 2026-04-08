<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('practice_turns', function (Blueprint $table) {
            $table->text('question')->nullable()->after('scenario');
            $table->foreignId('generated_questions_id')
                ->nullable()
                ->constrained('generated_questions')
                ->nullOnDelete()
                ->after('question');

            $table->index(['user_id', 'generated_questions_id']);
        });
    }

    public function down(): void
    {
        Schema::table('practice_turns', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'generated_questions_id']);
            $table->dropConstrainedForeignId('generated_questions_id');
            $table->dropColumn('question');
        });
    }
};