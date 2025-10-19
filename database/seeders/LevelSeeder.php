<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelPrerequisite;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Начальный',
                'slug' => 'beginner',
                'description' => 'Основы предмета для новичков',
                'order' => 1,
                'min_hours' => 20,
                'max_hours' => 40,
                'learning_outcomes' => ['понимание основ', 'базовые навыки'],
                'prerequisite_level_id' => null,
                'requires_completion' => false,
                'min_score' => 0,
                'completion_criteria' => ['посещение 80% занятий', 'выполнение базовых заданий']
            ],
            [
                'name' => 'Базовый',
                'slug' => 'basic',
                'description' => 'Фундаментальные знания по предмету',
                'order' => 2,
                'min_hours' => 40,
                'max_hours' => 80,
                'learning_outcomes' => ['системные знания', 'практические навыки'],
                'prerequisite_level_id' => 1, // Требует завершения Начального
                'requires_completion' => true,
                'min_score' => 70,
                'completion_criteria' => ['успешная сдача тестов', 'выполнение проектов']
            ],
            [
                'name' => 'Продвинутый',
                'slug' => 'intermediate',
                'description' => 'Углубленное изучение предмета',
                'order' => 3,
                'min_hours' => 80,
                'max_hours' => 120,
                'learning_outcomes' => ['углубленные знания', 'проектная работа'],
                'prerequisite_level_id' => 2, // Требует завершения Базового
                'requires_completion' => true,
                'min_score' => 75,
                'completion_criteria' => ['защита проекта', 'сдача экзамена']
            ],
            [
                'name' => 'Профильный',
                'slug' => 'advanced',
                'description' => 'Специализация и экспертные знания',
                'order' => 4,
                'min_hours' => 120,
                'max_hours' => 200,
                'learning_outcomes' => ['экспертные знания', 'исследовательская работа'],
                'prerequisite_level_id' => 3, // Требует завершения Продвинутого
                'requires_completion' => true,
                'min_score' => 80,
                'completion_criteria' => ['научная работа', 'экспертная оценка']
            ]
        ];

        foreach ($levels as $levelData) {
            $prerequisiteId = $levelData['prerequisite_level_id'];
            unset($levelData['prerequisite_level_id']);

            $level = Level::create($levelData);

            // Создаем prerequisite связь если нужно
            if ($prerequisiteId) {
                LevelPrerequisite::create([
                    'level_id' => $level->id,
                    'required_level_id' => $prerequisiteId,
                    'min_score' => $levelData['min_score'],
                    'require_final_exam' => true
                ]);
            }
        }

        $this->command->info('Уровни сложности созданы успешно!');
    }
}
