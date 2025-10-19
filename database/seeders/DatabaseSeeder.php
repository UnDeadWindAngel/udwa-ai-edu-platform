<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            LevelSeeder::class,
            SubjectSeeder::class,
            AdminUserSeeder::class,
            TeacherUserSeeder::class,
            StudentUserSeeder::class,
            ParentUserSeeder::class,
            CurriculumSeeder::class,
            // PermissionSeeder можно добавить позже
        ]);
    }
}
