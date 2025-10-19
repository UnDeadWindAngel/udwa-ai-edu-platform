<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Информатика',
                'slug' => 'informatics',
                'description' => 'Основы программирования, алгоритмы и компьютерные науки',
                'color' => '#007bff',
                'icon' => 'fas fa-laptop-code',
                'order' => 1,
                'is_active' => true,
                'metadata' => ['has_programming' => true, 'has_math' => true]
            ],
            [
                'name' => 'Математика',
                'slug' => 'mathematics',
                'description' => 'Алгебра, геометрия, математический анализ',
                'color' => '#dc3545',
                'icon' => 'fas fa-calculator',
                'order' => 2,
                'is_active' => true,
                'metadata' => ['has_calculations' => true, 'has_proofs' => true]
            ],
            [
                'name' => 'Физика',
                'slug' => 'physics',
                'description' => 'Механика, оптика, электричество и магнетизм',
                'color' => '#28a745',
                'icon' => 'fas fa-atom',
                'order' => 3,
                'is_active' => true,
                'metadata' => ['has_labs' => true, 'has_experiments' => true]
            ],
            [
                'name' => 'Русский язык',
                'slug' => 'russian-language',
                'description' => 'Грамматика, орфография, пунктуация и развитие речи',
                'color' => '#ffc107',
                'icon' => 'fas fa-book',
                'order' => 4,
                'is_active' => true,
                'metadata' => ['has_grammar' => true, 'has_literature' => true]
            ],
            [
                'name' => 'История',
                'slug' => 'history',
                'description' => 'Всемирная история, история России',
                'color' => '#6f42c1',
                'icon' => 'fas fa-landmark',
                'order' => 5,
                'is_active' => true,
                'metadata' => ['has_dates' => true, 'has_events' => true]
            ]
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Предметы созданы успешно!');
    }
}
