<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы для связи родителей с детьми.
     */
    public function up(): void
    {
        Schema::create('parent_student_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('relationship_type'); // mother, father, guardian, other
            $table->boolean('is_primary')->default(false);
            $table->boolean('can_view_progress')->default(true);
            $table->boolean('can_receive_notifications')->default(true);
            $table->json('permissions')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique(['parent_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_student_relationships');
    }
};
