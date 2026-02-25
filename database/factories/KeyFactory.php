<?php

namespace Database\Factories;

use App\Models\Key;
use App\Models\System;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeyFactory extends Factory
{
    protected $model = Key::class;

    public function definition(): array
    {
        return [
            'system_id' => System::factory(),
            'title' => $this->faker->unique()->bothify('??-###'),
        ];
    }
}
