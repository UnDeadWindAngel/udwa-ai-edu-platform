<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherUserSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'first_name' => 'Иван',
                'last_name' => 'Петров',
                'middle_name' => 'Сергеевич',
                'email' => 'teacher.informatics@udwa.edu',
                'password' => Hash::make('password123'),
                'phone' => '+79990000002',
                'bio' => 'Опытный преподаватель информатики с 10-летним стажем. Специализация: программирование на Python, веб-разработка.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Мария',
                'last_name' => 'Сидорова',
                'middle_name' => 'Ивановна',
                'email' => 'teacher.mathematics@udwa.edu',
                'password' => Hash::make('password123'),
                'phone' => '+79990000003',
                'bio' => 'Преподаватель математики. Специализация: подготовка к ЕГЭ, олимпиадная математика.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Алексей',
                'last_name' => 'Козлов',
                'middle_name' => 'Викторович',
                'email' => 'teacher.physics@udwa.edu',
                'password' => Hash::make('password123'),
                'phone' => '+79990000004',
                'bio' => 'Преподаватель физики. Увлекаюсь экспериментальной физикой и астрономией.',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        ];

        $teacherRole = Role::where('name', Role::TEACHER)->first();

        foreach ($teachers as $teacherData) {
            $teacherData['name'] = "{$teacherData['first_name']} {$teacherData['last_name']}";
            $teacher = User::create($teacherData);
            $teacher->roles()->attach($teacherRole);
        }

        $this->command->info('Учителя созданы успешно!');
    }
}
