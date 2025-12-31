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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Recipient
            $table->enum('type', ['sms', 'whatsapp', 'push']);
            $table->text('content');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('reference_id')->nullable(); // Order ID or Event ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
