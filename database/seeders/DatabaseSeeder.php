<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $scenarios = [
            ['key' => 'job_interview', 'label' => 'Job interview'],
            ['key' => 'technical_interview', 'label' => 'Technical interview'],
            ['key' => 'behavioral_interview', 'label' => 'Behavioral interview'],
            ['key' => 'daily_standup', 'label' => 'Daily standup'],
            ['key' => 'explaining_a_bug', 'label' => 'Explaining a bug'],
            ['key' => 'code_review', 'label' => 'Code review'],
            ['key' => 'talking_to_a_client', 'label' => 'Talking to a client'],
            ['key' => 'explaining_rest_api', 'label' => 'Explaining REST API'],
            ['key' => 'talking_about_laravel_project', 'label' => 'Talking about Laravel project'],
            ['key' => 'describing_system_architecture', 'label' => 'Describing system architecture'],
        ];

        foreach ($scenarios as $scenario) {
            \App\Models\Scenario::query()->updateOrCreate(
                ['key' => $scenario['key']],
                ['label' => $scenario['label']]
            );
        }

        if (! User::query()->where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call(LessonSeeder::class);
        $this->call(PlacementTestSeeder::class);
        $this->call(BadgeSeeder::class);
    }
}
