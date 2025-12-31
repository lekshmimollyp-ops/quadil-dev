<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Big Corporate
        $corp = \App\Models\Tenant::firstOrCreate(['name' => 'Global Retail Corp'], [
            'type' => 'corporate',
            'domain' => 'global-retail.com',
            'is_active' => true,
        ]);

        // 2. Store Chain under Corp
        $chain = \App\Models\Tenant::firstOrCreate(['name' => 'Fast Food Chain'], [
            'parent_tenant_id' => $corp->id,
            'type' => 'chain',
            'domain' => 'fastfood.com',
            'is_active' => true,
        ]);

        // 3. Independent Single Store
        \App\Models\Tenant::firstOrCreate(['name' => 'Local Corner Store'], [
            'type' => 'single',
            'domain' => 'cornerstore.local',
            'is_active' => true,
        ]);
    }
}
