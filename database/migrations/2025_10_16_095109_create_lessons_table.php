<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы уроки.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('curriculum_topic_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->integer('order')->default(0);
            $table->integer('estimated_duration');
            $table->string('difficulty_level'); // easy, medium, hard
            $table->json('learning_objectives')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('requires_approval')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
