<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'first_name' => 'Анна',
                'last_name' => 'Иванова',
                'middle_name' => 'Петровна',
                'email' => 'student.anna@udwa.edu',
                'password' => Hash::make('password123'),
                'birth_date' => '2008-05-15',
                'phone' => '+79990000005',
                'grade' => '9 класс',
                'student_id' => 'STU001',
                'bio' => 'Увлекаюсь программированием и робототехникой. Участвую в олимпиадах по информатике.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Дмитрий',
                'last_name' => 'Смирнов',
                'middle_name' => 'Александрович',
                'email' => 'student.dmitry@udwa.edu',
                'password' => Hash::make('password123'),
                'birth_date' => '2007-08-22',
                'phone' => '+79990000006',
                'grade' => '10 класс',
                'student_id' => 'STU002',
                'bio' => 'Интересуюсь математикой и физикой. Мечтаю стать инженером.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Екатерина',
                'last_name' => 'Кузнецова',
                'middle_name' => 'Сергеевна',
                'email' => 'student.ekaterina@udwa.edu',
                'password' => Hash::make('password123'),
                'birth_date' => '2009-02-10',
                'phone' => '+79990000007',
                'grade' => '8 класс',
                'student_id' => 'STU003',
                'bio' => 'Люблю литературу и историю. Пишу рассказы и участвую в литературных конкурсах.',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        ];

        $studentRole = Role::where('name', Role::STUDENT)->first();

        foreach ($students as $studentData) {
            $studentData['name'] = "{$studentData['first_name']} {$studentData['last_name']}";
            $student = User::create($studentData);
            $student->roles()->attach($studentRole);
        }

        $this->command->info('Студенты созданы успешно!');
    }
}
