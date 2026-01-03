<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\State;
use App\Models\Country;

class GeoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Countries Exist
        $countries = [
            ['code' => 'IND', 'name' => 'India'],
            ['code' => 'AE', 'name' => 'United Arab Emirates'],
            ['code' => 'GB', 'name' => 'United Kingdom'],
            ['code' => 'US', 'name' => 'United States'],
            ['code' => 'SA', 'name' => 'Saudi Arabia'],
            ['code' => 'CA', 'name' => 'Canada'],
            ['code' => 'AU', 'name' => 'Australia'],
            ['code' => 'SG', 'name' => 'Singapore'],
        ];

        foreach ($countries as $c) {
            Country::firstOrCreate(['code' => $c['code']], ['name' => $c['name']]);
        }

        // 2. Rich City Dataset (50+ Major Cities)
        $cities = [
            // INDIA
            ['name' => 'Mumbai', 'state' => 'Maharashtra', 'code' => 'IND'],
            ['name' => 'Delhi', 'state' => 'Delhi', 'code' => 'IND'],
            ['name' => 'Bangalore', 'state' => 'Karnataka', 'code' => 'IND'],
            ['name' => 'Hyderabad', 'state' => 'Telangana', 'code' => 'IND'],
            ['name' => 'Ahmedabad', 'state' => 'Gujarat', 'code' => 'IND'],
            ['name' => 'Chennai', 'state' => 'Tamil Nadu', 'code' => 'IND'],
            ['name' => 'Kolkata', 'state' => 'West Bengal', 'code' => 'IND'],
            ['name' => 'Pune', 'state' => 'Maharashtra', 'code' => 'IND'],
            ['name' => 'Jaipur', 'state' => 'Rajasthan', 'code' => 'IND'],
            ['name' => 'Surat', 'state' => 'Gujarat', 'code' => 'IND'],
            ['name' => 'Lucknow', 'state' => 'Uttar Pradesh', 'code' => 'IND'],
            ['name' => 'Kanpur', 'state' => 'Uttar Pradesh', 'code' => 'IND'],
            ['name' => 'Nagpur', 'state' => 'Maharashtra', 'code' => 'IND'],
            ['name' => 'Indore', 'state' => 'Madhya Pradesh', 'code' => 'IND'],
            ['name' => 'Thane', 'state' => 'Maharashtra', 'code' => 'IND'],
            ['name' => 'Bhopal', 'state' => 'Madhya Pradesh', 'code' => 'IND'],
            ['name' => 'Visakhapatnam', 'state' => 'Andhra Pradesh', 'code' => 'IND'],
            ['name' => 'Patna', 'state' => 'Bihar', 'code' => 'IND'],
            ['name' => 'Vadodara', 'state' => 'Gujarat', 'code' => 'IND'],
            ['name' => 'Kochi', 'state' => 'Kerala', 'code' => 'IND'],
            ['name' => 'Trivandrum', 'state' => 'Kerala', 'code' => 'IND'],
            ['name' => 'Calicut', 'state' => 'Kerala', 'code' => 'IND'],
            ['name' => 'Coimbatore', 'state' => 'Tamil Nadu', 'code' => 'IND'],
            ['name' => 'Bhubaneswar', 'state' => 'Odisha', 'code' => 'IND'],
            ['name' => 'Guwahati', 'state' => 'Assam', 'code' => 'IND'],

            // UAE
            ['name' => 'Dubai City', 'state' => 'Dubai', 'code' => 'AE'],
            ['name' => 'Abu Dhabi', 'state' => 'Abu Dhabi', 'code' => 'AE'],
            ['name' => 'Sharjah', 'state' => 'Sharjah', 'code' => 'AE'],
            ['name' => 'Ajman', 'state' => 'Ajman', 'code' => 'AE'],

            // UK
            ['name' => 'London', 'state' => 'England', 'code' => 'GB'],
            ['name' => 'Manchester', 'state' => 'England', 'code' => 'GB'],
            ['name' => 'Birmingham', 'state' => 'England', 'code' => 'GB'],
            ['name' => 'Glasgow', 'state' => 'Scotland', 'code' => 'GB'],

            // USA
            ['name' => 'New York', 'state' => 'New York', 'code' => 'US'],
            ['name' => 'San Francisco', 'state' => 'California', 'code' => 'US'],
            ['name' => 'Los Angeles', 'state' => 'California', 'code' => 'US'],
            ['name' => 'Chicago', 'state' => 'Illinois', 'code' => 'US'],
            ['name' => 'Houston', 'state' => 'Texas', 'code' => 'US'],
            ['name' => 'Miami', 'state' => 'Florida', 'code' => 'US'],

            // OTHER
            ['name' => 'Riyadh', 'state' => 'Riyadh', 'code' => 'SA'],
            ['name' => 'Jeddah', 'state' => 'Makkah', 'code' => 'SA'],
            ['name' => 'Toronto', 'state' => 'Ontario', 'code' => 'CA'],
            ['name' => 'Vancouver', 'state' => 'British Columbia', 'code' => 'CA'],
            ['name' => 'Sydney', 'state' => 'New South Wales', 'code' => 'AU'],
            ['name' => 'Melbourne', 'state' => 'Victoria', 'code' => 'AU'],
            ['name' => 'Singapore', 'state' => 'Singapore', 'code' => 'SG'],
        ];

        foreach ($cities as $city) {
            // Find or Create State (Mock ID lookup if needed, but here we just store string if schema is flat)
            // Note: Adjusting to match the flat schema of create_cities_table (name, state, country_code)
            
            City::firstOrCreate(
                ['name' => $city['name']],
                [
                    'state' => $city['state'],
                    'country_code' => $city['code'],
                    'is_active' => true
                ]
            );
        }
    }
}
