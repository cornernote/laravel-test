<?php

namespace Database\Factories;

use App\Models\Lock;
use App\Models\System;
use Illuminate\Database\Eloquent\Factories\Factory;

class LockFactory extends Factory
{
    protected $model = Lock::class;

    public function definition(): array
    {
        return [
            'system_id' => System::factory(),
            'title' => $this->faker->unique()->bothify('??-###'),
        ];
    }
}
