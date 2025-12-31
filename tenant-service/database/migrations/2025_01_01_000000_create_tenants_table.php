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
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['corporate', 'chain', 'single']);
            $table->string('name');
            $table->string('domain')->unique(); // e.g., 'store-a.quadil.com'
            $table->json('settings')->nullable(); // Branding, colors, etc.
            $table->uuid('parent_tenant_id')->nullable(); // For chains
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
