<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы предварительных требований.
     */
    public function up(): void
    {
        Schema::create('level_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->foreignId('required_level_id')->constrained('levels')->onDelete('cascade');
            $table->integer('min_score')->default(70);
            $table->boolean('require_final_exam')->default(true);
            $table->json('additional_requirements')->nullable();
            $table->timestamps();

            $table->unique(['level_id', 'required_level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level_prerequisites');
    }
};
