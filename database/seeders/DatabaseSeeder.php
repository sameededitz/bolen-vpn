<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Plan;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // Create one admin user
        User::factory()->admin()->create();

        // Create one regular user
        User::factory()->user()->create();

        // Plan::factory()->trial()->create();

        Option::factory()->trialDays()->create();

        $this->call([
            PlanSeeder::class,
        ]);

        // Purchase::create([
        //     'user_id' => 2,
        //     'plan_id' => 1,
        //     'amount_paid' => 0.00, // Assuming free trial
        //     'status' => 'active',
        //     'start_date' => now(),
        //     'end_date' => now()->addDays(3),
        // ]);
    }
}
