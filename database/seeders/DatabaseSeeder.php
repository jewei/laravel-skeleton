<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->firstOrCreate([
            'id' => 1,
        ], [
            'name' => 'System',
            'email' => 'system@example.com',
            'password' => '', // root user can't login
            'locale' => 'en',
            'timezone' => 'UTC',
            'source' => 'Seeder',
        ]);

        // Inspired by https://github.com/ghost.
        User::factory()->firstOrCreate([
            'id' => 0,
        ], [
            'name' => 'Ghost',
            'email' => 'deleted-user@example.com',
            'password' => '', // ghost user can't login
            'locale' => 'en',
            'timezone' => 'UTC',
            'source' => 'Seeder',
        ]);
    }
}
