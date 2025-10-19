<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Добавляем поля в таблицу пользователи.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Основная информация
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();

            // Адреса
            $table->string('registration_address')->nullable(); // Прописка
            $table->string('residential_address')->nullable(); // Проживание
            $table->boolean('same_address')->default(false);

            // Дополнительные поля
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('language')->default('ru');

            // Для студентов
            $table->string('student_id')->nullable()->unique();
            $table->string('grade')->nullable();

            // Для родителей
            $table->json('emergency_contacts')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'middle_name', 'birth_date', 'phone',
                'registration_address', 'residential_address', 'same_address',
                'avatar', 'bio', 'is_active', 'last_login_at', 'timezone', 'language',
                'student_id', 'grade', 'emergency_contacts'
            ]);
        });
    }
};
