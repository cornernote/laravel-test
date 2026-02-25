<?php

namespace Database\Seeders;

use App\Models\Lock;
use Illuminate\Database\Seeder;

class LockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lock::factory()->count(5)->create();
    }
}
