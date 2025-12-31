<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Countries
        $uae = \App\Models\Country::firstOrCreate(['code' => 'AE'], ['name' => 'United Arab Emirates']);
        $uk = \App\Models\Country::firstOrCreate(['code' => 'GB'], ['name' => 'United Kingdom']);
        $us = \App\Models\Country::firstOrCreate(['code' => 'US'], ['name' => 'United States']);

        // 2. States
        $dubai = \App\Models\State::firstOrCreate(['country_id' => $uae->id, 'name' => 'Dubai']);
        $london = \App\Models\State::firstOrCreate(['country_id' => $uk->id, 'name' => 'Greater London']);
        $cali = \App\Models\State::firstOrCreate(['country_id' => $us->id, 'name' => 'California']);

        // 3. Cities
        \App\Models\City::firstOrCreate(['name' => 'Dubai City'], [
            'state_id' => $dubai->id,
            'state' => 'Dubai',
            'country_code' => 'AE'
        ]);

        \App\Models\City::firstOrCreate(['name' => 'San Francisco'], [
            'state_id' => $cali->id,
            'state' => 'California',
            'country_code' => 'US'
        ]);
    }
}
