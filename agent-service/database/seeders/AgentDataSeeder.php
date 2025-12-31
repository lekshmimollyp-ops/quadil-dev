<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\Vehicle;

class AgentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pending Agent - Needs Approval
        $pendingAgent1 = Agent::firstOrCreate(
            ['user_id' => 1001],
            [
                'is_active' => false,
                'current_status' => 'offline',
            ]
        );

        Vehicle::firstOrCreate(
            ['agent_id' => $pendingAgent1->id, 'plate_number' => 'MH01AB1234'],
            [
                'vehicle_type' => 'bike',
                'is_verified' => false,
            ]
        );

        // 2. Another Pending Agent
        $pendingAgent2 = Agent::firstOrCreate(
            ['user_id' => 1002],
            [
                'is_active' => false,
                'current_status' => 'offline',
            ]
        );

        Vehicle::firstOrCreate(
            ['agent_id' => $pendingAgent2->id, 'plate_number' => 'DL02CD5678'],
            [
                'vehicle_type' => 'bike',
                'is_verified' => false,
            ]
        );

        // 3. Approved Agent - Active
        $approvedAgent1 = Agent::firstOrCreate(
            ['user_id' => 1003],
            [
                'is_active' => true,
                'current_status' => 'online',
            ]
        );

        Vehicle::firstOrCreate(
            ['agent_id' => $approvedAgent1->id, 'plate_number' => 'KA03EF9012'],
            [
                'vehicle_type' => 'scooter',
                'is_verified' => true,
            ]
        );

        // 4. Another Approved Agent - Offline
        $approvedAgent2 = Agent::firstOrCreate(
            ['user_id' => 1004],
            [
                'is_active' => true,
                'current_status' => 'offline',
            ]
        );

        Vehicle::firstOrCreate(
            ['agent_id' => $approvedAgent2->id, 'plate_number' => 'TN04GH3456'],
            [
                'vehicle_type' => 'bike',
                'is_verified' => true,
            ]
        );

        // 5. Approved Agent - Away
        $approvedAgent3 = Agent::firstOrCreate(
            ['user_id' => 1005],
            [
                'is_active' => true,
                'current_status' => 'away',
            ]
        );

        Vehicle::firstOrCreate(
            ['agent_id' => $approvedAgent3->id, 'plate_number' => 'UP05IJ7890'],
            [
                'vehicle_type' => 'scooter',
                'is_verified' => true,
            ]
        );
    }
}
