<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use App\Models\CurriculumModule;
use App\Models\CurriculumTopic;
use App\Models\Level;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('email', 'teacher.informatics@udwa.edu')->first();
        $informatics = Subject::where('slug', 'informatics')->first();
        $beginnerLevel = Level::where('slug', 'beginner')->first();

        // Учебный план по информатике для начального уровня
        $curriculum = Curriculum::create([
            'subject_id' => $informatics->id,
            'level_id' => $beginnerLevel->id,
            'name' => 'Основы программирования для начинающих',
            'version' => '1.0',
            'description' => 'Введение в программирование на Python для школьников',
            'total_hours' => 40,
            'theory_hours' => 15,
            'practice_hours' => 25,
            'weekly_schedule' => [
                'weeks' => 10,
                'hours_per_week' => 4,
                'schedule' => '2 раза в неделю по 2 часа'
            ],
            'effective_from' => now(),
            'effective_to' => now()->addYear(),
            'is_active' => true,
            'created_by' => $teacher->id
        ]);

        // Модули учебного плана
        $modules = [
            [
                'name' => 'Введение в программирование',
                'description' => 'Основные понятия и первая программа',
                'order' => 1,
                'total_hours' => 8,
                'theory_hours' => 3,
                'practice_hours' => 5,
                'learning_objectives' => ['понимание алгоритмов', 'написание первой программы']
            ],
            [
                'name' => 'Переменные и типы данных',
                'description' => 'Работа с данными в программах',
                'order' => 2,
                'total_hours' => 10,
                'theory_hours' => 4,
                'practice_hours' => 6,
                'learning_objectives' => ['работа с переменными', 'понимание типов данных']
            ],
            [
                'name' => 'Условные операторы и циклы',
                'description' => 'Управление потоком выполнения программы',
                'order' => 3,
                'total_hours' => 12,
                'theory_hours' => 5,
                'practice_hours' => 7,
                'learning_objectives' => ['использование условий', 'работа с циклами']
            ],
            [
                'name' => 'Функции и модули',
                'description' => 'Структурирование кода',
                'order' => 4,
                'total_hours' => 10,
                'theory_hours' => 3,
                'practice_hours' => 7,
                'learning_objectives' => ['создание функций', 'использование модулей']
            ]
        ];

        foreach ($modules as $moduleData) {
            $module = CurriculumModule::create(array_merge($moduleData, [
                'curriculum_id' => $curriculum->id
            ]));

            // Добавляем темы для каждого модуля
            $this->createTopicsForModule($module);
        }

        $this->command->info('Учебные планы созданы успешно!');
    }

    private function createTopicsForModule(CurriculumModule $module)
    {
        $topics = [];

        switch ($module->order) {
            case 1: // Введение в программирование
                $topics = [
                    ['name' => 'Что такое программирование?', 'estimated_hours' => 2, 'topic_type' => 'theory'],
                    ['name' => 'Установка Python и среды разработки', 'estimated_hours' => 2, 'topic_type' => 'practice'],
                    ['name' => 'Первая программа: Hello World', 'estimated_hours' => 2, 'topic_type' => 'practice'],
                    ['name' => 'Основы алгоритмов', 'estimated_hours' => 2, 'topic_type' => 'theory'],
                ];
                break;
            case 2: // Переменные и типы данных
                $topics = [
                    ['name' => 'Переменные и присваивание', 'estimated_hours' => 2, 'topic_type' => 'theory'],
                    ['name' => 'Числовые типы данных', 'estimated_hours' => 3, 'topic_type' => 'practice'],
                    ['name' => 'Строки и операции со строками', 'estimated_hours' => 3, 'topic_type' => 'practice'],
                    ['name' => 'Логический тип данных', 'estimated_hours' => 2, 'topic_type' => 'theory'],
                ];
                break;
            // ... аналогично для других модулей
        }

        foreach ($topics as $index => $topicData) {
            CurriculumTopic::create(array_merge($topicData, [
                'module_id' => $module->id,
                'order' => $index + 1,
                'description' => "Тема модуля '{$module->name}'",
                'resources' => ['presentation', 'video', 'practice_tasks'],
                'assessment_criteria' => ['понимание теории', 'выполнение практических заданий']
            ]));
        }
    }
}
