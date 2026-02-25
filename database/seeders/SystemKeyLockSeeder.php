<?php

namespace Database\Seeders;

use App\Models\System;
use App\Models\Key;
use App\Models\Lock;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Random\RandomException;

class SystemKeyLockSeeder extends Seeder
{
    public function run(): void
    {
        $systemsCount = 80;
        System::factory()
            ->count($systemsCount)
            ->create()
            ->each(function (System $system) {
                try {
                    $keysPerSystem = random_int(10, 50);
                    $locksPerSystem = random_int(10, 500);
                } catch (RandomException $e) {
                    $keysPerSystem = 10;
                    $locksPerSystem = 10;
                }

                /** @var Collection $keys <int, \App\Models\Key> */
                $keys = Key::factory()
                    ->count($keysPerSystem)
                    ->create(['system_id' => $system->id]);

                /** @var Collection $locks <int, \App\Models\Lock> */
                $locks = Lock::factory()
                    ->count($locksPerSystem)
                    ->create(['system_id' => $system->id]);

                // attach each key to random locks
                $keys->each(function (Key $key) use ($locks) {
                    try {
                        $howManyLocks = random_int(1, (int)($locks->count() / 10));
                    } catch (RandomException $e) {
                        $howManyLocks = 1;
                    }
                    $lockIds = $locks->random($howManyLocks)->pluck('id')->all();

                    // syncWithoutDetaching prevents duplicates if the seeder re-runs
                    $key->locks()->syncWithoutDetaching($lockIds);
                });
            });
    }
}
