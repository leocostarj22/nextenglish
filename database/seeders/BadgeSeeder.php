<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Module completion badges
            ['slug' => 'a1-complete', 'name' => 'Primeiros Passos', 'description' => 'Completou o módulo A1 — Fundamentos', 'icon' => '🌱', 'condition_type' => 'module_complete', 'condition_value' => 'A1'],
            ['slug' => 'a2-complete', 'name' => 'Blocos Essenciais', 'description' => 'Completou o módulo A2 — Elementar', 'icon' => '🧱', 'condition_type' => 'module_complete', 'condition_value' => 'A2'],
            ['slug' => 'b1-complete', 'name' => 'Fluência em Construção', 'description' => 'Completou o módulo B1 — Intermediário', 'icon' => '💬', 'condition_type' => 'module_complete', 'condition_value' => 'B1'],
            ['slug' => 'b2-complete', 'name' => 'Comunicador Avançado', 'description' => 'Completou o módulo B2 — Pré-Avançado', 'icon' => '🎯', 'condition_type' => 'module_complete', 'condition_value' => 'B2'],
            ['slug' => 'c1-complete', 'name' => 'Quase Nativo', 'description' => 'Completou o módulo C1 — Avançado', 'icon' => '⭐', 'condition_type' => 'module_complete', 'condition_value' => 'C1'],
            ['slug' => 'c2-complete', 'name' => 'Mestre do Inglês', 'description' => 'Completou o módulo C2 — Domínio', 'icon' => '👑', 'condition_type' => 'module_complete', 'condition_value' => 'C2'],

            // XP milestones
            ['slug' => 'xp-100', 'name' => 'Primeira Faísca', 'description' => 'Acumulou 100 XP', 'icon' => '⚡', 'condition_type' => 'xp_milestone', 'condition_value' => '100'],
            ['slug' => 'xp-500', 'name' => 'Aprendiz Dedicado', 'description' => 'Acumulou 500 XP', 'icon' => '📚', 'condition_type' => 'xp_milestone', 'condition_value' => '500'],
            ['slug' => 'xp-1000', 'name' => 'Mil XP!', 'description' => 'Acumulou 1.000 XP', 'icon' => '🔥', 'condition_type' => 'xp_milestone', 'condition_value' => '1000'],
            ['slug' => 'xp-2000', 'name' => 'Expert em Inglês', 'description' => 'Acumulou 2.000 XP', 'icon' => '💎', 'condition_type' => 'xp_milestone', 'condition_value' => '2000'],

            // Streak badges
            ['slug' => 'streak-3', 'name' => 'Em Chamas', 'description' => 'Estudou 3 dias seguidos', 'icon' => '🔥', 'condition_type' => 'streak', 'condition_value' => '3'],
            ['slug' => 'streak-7', 'name' => 'Guerreiro Semanal', 'description' => 'Estudou 7 dias seguidos', 'icon' => '📅', 'condition_type' => 'streak', 'condition_value' => '7'],
            ['slug' => 'streak-30', 'name' => 'Mestre da Consistência', 'description' => 'Estudou 30 dias seguidos', 'icon' => '🏆', 'condition_type' => 'streak', 'condition_value' => '30'],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
