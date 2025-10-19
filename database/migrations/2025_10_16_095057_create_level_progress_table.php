<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы прогресса по уровням.
     */
    public function up(): void
    {
        Schema::create('level_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'locked'])->default('not_started');
            $table->integer('progress_percentage')->default(0);
            $table->integer('score')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('assessment_results')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level_progress');
    }
};
