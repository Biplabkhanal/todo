<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\todos;
use Database\Factories\TodoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        todos::factory()
            ->has(Image::factory()->count(1))
            ->count(3)
            ->create();

    }
}
