<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => Role::ADMIN,
                'display_name' => 'Администратор UDWA',
                'description' => 'Полный доступ ко всем функциям платформы UDWA'
            ],
            [
                'name' => Role::TEACHER,
                'display_name' => 'Учитель UDWA',
                'description' => 'Может создавать контент и проверять работы студентов'
            ],
            [
                'name' => Role::MODERATOR,
                'display_name' => 'Модератор UDWA',
                'description' => 'Может модерировать контент и управлять курсами'
            ],
            [
                'name' => Role::STUDENT,
                'display_name' => 'Студент UDWA',
                'description' => 'Основной пользователь, изучает материалы на платформе'
            ],
            [
                'name' => Role::PARENT,
                'display_name' => 'Родитель UDWA',
                'description' => 'Может просматривать успеваемость своих детей'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('Роли созданы успешно!');
    }
}
