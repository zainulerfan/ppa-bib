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
        User::firstOrCreate(
            ['email' => 'admin@ppa-bib.com'],
            [
                'name' => 'HC Admin BIB',
                'password' => 'password',
            ]
        );

        User::firstOrCreate(
            ['email' => 'hc@ppa-bib.com'],
            [
                'name' => 'HC Recruitment Team',
                'password' => 'password',
            ]
        );
    }
}
