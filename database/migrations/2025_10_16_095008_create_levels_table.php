<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы уровней.
     */
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Начальный, Базовый, Продвинутый, Профильный
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->integer('min_hours')->default(0);
            $table->integer('max_hours')->default(0);
            $table->json('learning_outcomes')->nullable();
            $table->foreignId('prerequisite_level_id')->nullable()->constrained('levels');
            $table->integer('min_score')->default(70);
            $table->boolean('requires_completion')->default(true);
            $table->json('completion_criteria')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
