<?php

namespace Database\Seeders;

use App\Models\Key;
use Illuminate\Database\Seeder;

class KeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Key::factory()->count(5)->create();
    }
}
