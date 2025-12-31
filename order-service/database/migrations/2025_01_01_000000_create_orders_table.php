<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id'); // From Tenant Service
            $table->unsignedBigInteger('user_id'); // Merchant/Admin who created it
            
            // Details stored as JSON for flexibility
            $table->json('pickup_details'); // address, lat, lng, contact_name, contact_phone
            $table->json('delivery_details'); // address, lat, lng, contact_name, contact_phone
            $table->json('parcel_details'); // weight, type, description, instructions
            
            $table->enum('status', [
                'draft', 
                'pending', 
                'assigned', 
                'picked_up', 
                'delivered', 
                'cancelled'
            ])->default('pending');
            
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
