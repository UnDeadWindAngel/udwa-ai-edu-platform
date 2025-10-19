<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы темы модулей.
     */
    public function up(): void
    {
        Schema::create('curriculum_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('curriculum_modules')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->integer('estimated_hours');
            $table->string('topic_type'); // theory, practice, project, test
            $table->json('resources')->nullable();
            $table->json('assessment_criteria')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_topics');
    }
};
