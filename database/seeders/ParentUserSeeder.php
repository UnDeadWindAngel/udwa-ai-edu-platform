<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentUserSeeder extends Seeder
{
    public function run(): void
    {
        $parents = [
            [
                'first_name' => 'Ольга',
                'last_name' => 'Иванова',
                'middle_name' => 'Владимировна',
                'email' => 'parent.olga@udwa.edu',
                'password' => Hash::make('password123'),
                'phone' => '+79990000008',
                'bio' => 'Мать двух школьников. Интересуюсь прогрессом детей в обучении.',
                'is_active' => true,
                'email_verified_at' => now(),
                'emergency_contacts' => [
                    'husband' => '+79990000009',
                    'school' => '+74950000001'
                ]
            ],
            [
                'first_name' => 'Сергей',
                'last_name' => 'Смирнов',
                'middle_name' => 'Игоревич',
                'email' => 'parent.sergey@udwa.edu',
                'password' => Hash::make('password123'),
                'phone' => '+79990000009',
                'bio' => 'Отец. Слежу за успехами сына в математике и физике.',
                'is_active' => true,
                'email_verified_at' => now(),
                'emergency_contacts' => [
                    'wife' => '+79990000008',
                    'work' => '+74950000002'
                ]
            ]
        ];

        $parentRole = Role::where('name', Role::PARENT)->first();

        foreach ($parents as $parentData) {
            $parentData['name'] = "{$parentData['first_name']} {$parentData['last_name']}";
            $parent = User::create($parentData);
            $parent->roles()->attach($parentRole);
        }

        $this->command->info('Родители созданы успешно!');
    }
}
