<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DriverDataSeeder extends Seeder
{
    /**
     * Seed online drivers for dispatch testing
     */
    public function run(): void
    {
        // Create 3 online drivers matching the agent user_ids
        $drivers = [
            [
                'id' => Str::uuid()->toString(),
                'user_id' => 1003,
                'vehicle_type' => 'Scooter',
                'license_number' => 'KA03EF9012',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'user_id' => 1004,
                'vehicle_type' => 'Bike',
                'license_number' => 'TN04GH3456',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'user_id' => 1005,
                'vehicle_type' => 'Scooter',
                'license_number' => 'UP05IJ7890',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }

        $this->command->info('✅ Created ' . count($drivers) . ' online drivers for dispatch');
        $this->command->info('   - Driver (User 1003): Scooter - Online');
        $this->command->info('   - Driver (User 1004): Bike - Online');
        $this->command->info('   - Driver (User 1005): Scooter - Online');
    }
}
