<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем администратора
        $admin = User::create([
            'name' => 'Администратор Системы',
            'email' => 'admin@udwa.edu',
            'password' => Hash::make('password123'),
            'first_name' => 'Администратор',
            'last_name' => 'Системы',
            'phone' => '+79990000001',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $admin->roles()->attach(Role::where('name', Role::ADMIN)->first());

        $this->command->info('Администратор создан: admin@udwa.edu / password123');
    }
}
