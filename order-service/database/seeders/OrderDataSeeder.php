<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates comprehensive test data for orders with all possible statuses
     * to support thorough testing of the Live Orders module.
     */
    public function run(): void
    {
        // Sample tenant IDs (from tenant-service)
        $tenantIds = [
            '11111111-1111-1111-1111-111111111111', // Global Retail Corp
            '22222222-2222-2222-2222-222222222222', // Fast Food Chain
            '33333333-3333-3333-3333-333333333333', // Local Corner Store
        ];

        // Sample user IDs (merchants/admins)
        $userIds = [1, 2, 3, 4];

        // Sample addresses in Bangalore
        $pickupAddresses = [
            [
                'address' => 'MG Road, Bangalore, Karnataka 560001',
                'lat' => 12.9716,
                'lng' => 77.5946,
                'contact_name' => 'Rajesh Kumar',
                'contact_phone' => '+91 98765 43210'
            ],
            [
                'address' => 'Koramangala 5th Block, Bangalore, Karnataka 560095',
                'lat' => 12.9352,
                'lng' => 77.6245,
                'contact_name' => 'Priya Sharma',
                'contact_phone' => '+91 98765 43211'
            ],
            [
                'address' => 'Indiranagar 100 Feet Road, Bangalore, Karnataka 560038',
                'lat' => 12.9719,
                'lng' => 77.6412,
                'contact_name' => 'Amit Patel',
                'contact_phone' => '+91 98765 43212'
            ],
        ];

        $deliveryAddresses = [
            [
                'address' => 'Whitefield Main Road, Bangalore, Karnataka 560066',
                'lat' => 12.9698,
                'lng' => 77.7499,
                'contact_name' => 'Sneha Reddy',
                'contact_phone' => '+91 98765 43220'
            ],
            [
                'address' => 'Electronic City Phase 1, Bangalore, Karnataka 560100',
                'lat' => 12.8456,
                'lng' => 77.6603,
                'contact_name' => 'Vikram Singh',
                'contact_phone' => '+91 98765 43221'
            ],
            [
                'address' => 'JP Nagar 7th Phase, Bangalore, Karnataka 560078',
                'lat' => 12.8996,
                'lng' => 77.5858,
                'contact_name' => 'Lakshmi Iyer',
                'contact_phone' => '+91 98765 43222'
            ],
            [
                'address' => 'HSR Layout Sector 1, Bangalore, Karnataka 560102',
                'lat' => 12.9121,
                'lng' => 77.6446,
                'contact_name' => 'Arjun Nair',
                'contact_phone' => '+91 98765 43223'
            ],
        ];

        $parcelTypes = [
            [
                'weight' => 2.5,
                'type' => 'Documents',
                'description' => 'Legal documents and contracts',
                'instructions' => 'Handle with care, keep dry'
            ],
            [
                'weight' => 5.0,
                'type' => 'Electronics',
                'description' => 'Laptop and accessories',
                'instructions' => 'Fragile - Handle with extreme care'
            ],
            [
                'weight' => 1.0,
                'type' => 'Food',
                'description' => 'Fresh bakery items',
                'instructions' => 'Deliver within 2 hours'
            ],
            [
                'weight' => 3.5,
                'type' => 'Clothing',
                'description' => 'Designer apparel',
                'instructions' => 'Keep away from moisture'
            ],
            [
                'weight' => 10.0,
                'type' => 'Groceries',
                'description' => 'Monthly grocery supplies',
                'instructions' => 'Check for perishables'
            ],
        ];

        $orders = [];

        // 1. DRAFT Orders (2 orders) - Just created, not yet confirmed
        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[0],
            'user_id' => $userIds[0],
            'pickup_details' => $pickupAddresses[0],
            'delivery_details' => $deliveryAddresses[0],
            'parcel_details' => $parcelTypes[0],
            'status' => 'draft',
            'total_amount' => 150.00,
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[1],
            'user_id' => $userIds[1],
            'pickup_details' => $pickupAddresses[1],
            'delivery_details' => $deliveryAddresses[1],
            'parcel_details' => $parcelTypes[1],
            'status' => 'draft',
            'total_amount' => 250.00,
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subHours(1),
        ];

        // 2. PENDING Orders (3 orders) - Confirmed, waiting for agent assignment
        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[0],
            'user_id' => $userIds[0],
            'pickup_details' => $pickupAddresses[0],
            'delivery_details' => $deliveryAddresses[2],
            'parcel_details' => $parcelTypes[2],
            'status' => 'pending',
            'total_amount' => 180.00,
            'created_at' => now()->subMinutes(45),
            'updated_at' => now()->subMinutes(45),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[1],
            'user_id' => $userIds[1],
            'pickup_details' => $pickupAddresses[1],
            'delivery_details' => $deliveryAddresses[3],
            'parcel_details' => $parcelTypes[3],
            'status' => 'pending',
            'total_amount' => 220.00,
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[2],
            'user_id' => $userIds[2],
            'pickup_details' => $pickupAddresses[2],
            'delivery_details' => $deliveryAddresses[0],
            'parcel_details' => $parcelTypes[4],
            'status' => 'pending',
            'total_amount' => 350.00,
            'created_at' => now()->subMinutes(15),
            'updated_at' => now()->subMinutes(15),
        ];

        // 3. ASSIGNED Orders (3 orders) - Agent assigned, not yet picked up
        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[0],
            'user_id' => $userIds[0],
            'pickup_details' => $pickupAddresses[0],
            'delivery_details' => $deliveryAddresses[1],
            'parcel_details' => $parcelTypes[0],
            'status' => 'assigned',
            'total_amount' => 200.00,
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subMinutes(20),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[1],
            'user_id' => $userIds[1],
            'pickup_details' => $pickupAddresses[1],
            'delivery_details' => $deliveryAddresses[2],
            'parcel_details' => $parcelTypes[1],
            'status' => 'assigned',
            'total_amount' => 275.00,
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subMinutes(10),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[2],
            'user_id' => $userIds[2],
            'pickup_details' => $pickupAddresses[2],
            'delivery_details' => $deliveryAddresses[3],
            'parcel_details' => $parcelTypes[2],
            'status' => 'assigned',
            'total_amount' => 190.00,
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subMinutes(5),
        ];

        // 4. PICKED_UP Orders (2 orders) - Agent has picked up, en route to delivery
        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[0],
            'user_id' => $userIds[0],
            'pickup_details' => $pickupAddresses[0],
            'delivery_details' => $deliveryAddresses[0],
            'parcel_details' => $parcelTypes[3],
            'status' => 'picked_up',
            'total_amount' => 310.00,
            'created_at' => now()->subHours(4),
            'updated_at' => now()->subMinutes(25),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[1],
            'user_id' => $userIds[1],
            'pickup_details' => $pickupAddresses[1],
            'delivery_details' => $deliveryAddresses[1],
            'parcel_details' => $parcelTypes[4],
            'status' => 'picked_up',
            'total_amount' => 420.00,
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subMinutes(15),
        ];

        // 5. DELIVERED Orders (4 orders) - Successfully completed
        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[0],
            'user_id' => $userIds[0],
            'pickup_details' => $pickupAddresses[0],
            'delivery_details' => $deliveryAddresses[2],
            'parcel_details' => $parcelTypes[0],
            'status' => 'delivered',
            'total_amount' => 175.00,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2)->addHours(2),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[1],
            'user_id' => $userIds[1],
            'pickup_details' => $pickupAddresses[1],
            'delivery_details' => $deliveryAddresses[3],
            'parcel_details' => $parcelTypes[1],
            'status' => 'delivered',
            'total_amount' => 285.00,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1)->addHours(3),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[2],
            'user_id' => $userIds[2],
            'pickup_details' => $pickupAddresses[2],
            'delivery_details' => $deliveryAddresses[0],
            'parcel_details' => $parcelTypes[2],
            'status' => 'delivered',
            'total_amount' => 195.00,
            'created_at' => now()->subHours(12),
            'updated_at' => now()->subHours(10),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[0],
            'user_id' => $userIds[0],
            'pickup_details' => $pickupAddresses[0],
            'delivery_details' => $deliveryAddresses[1],
            'parcel_details' => $parcelTypes[3],
            'status' => 'delivered',
            'total_amount' => 230.00,
            'created_at' => now()->subHours(8),
            'updated_at' => now()->subHours(6),
        ];

        // 6. CANCELLED Orders (2 orders) - Cancelled by customer or system
        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[1],
            'user_id' => $userIds[1],
            'pickup_details' => $pickupAddresses[1],
            'delivery_details' => $deliveryAddresses[2],
            'parcel_details' => $parcelTypes[4],
            'status' => 'cancelled',
            'total_amount' => 0.00,
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3)->addMinutes(30),
        ];

        $orders[] = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantIds[2],
            'user_id' => $userIds[2],
            'pickup_details' => $pickupAddresses[2],
            'delivery_details' => $deliveryAddresses[3],
            'parcel_details' => $parcelTypes[0],
            'status' => 'cancelled',
            'total_amount' => 0.00,
            'created_at' => now()->subHours(6),
            'updated_at' => now()->subHours(5),
        ];

        // Insert all orders
        foreach ($orders as $order) {
            Order::create($order);
        }

        $this->command->info('✅ Created ' . count($orders) . ' orders with the following distribution:');
        $this->command->info('   - Draft: 2 orders');
        $this->command->info('   - Pending: 3 orders');
        $this->command->info('   - Assigned: 3 orders');
        $this->command->info('   - Picked Up: 2 orders');
        $this->command->info('   - Delivered: 4 orders');
        $this->command->info('   - Cancelled: 2 orders');
        $this->command->info('   Total: 16 orders across all statuses');
    }
}
