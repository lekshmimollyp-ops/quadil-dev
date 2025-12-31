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
        Schema::create('pods', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->uuid('agent_id');
            $table->enum('type', ['otp', 'signature', 'photo']);
            $table->string('value'); // The OTP code or file path to signature/photo
            $table->boolean('is_verified')->default(false);
            $table->timestamp('captured_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pods');
    }
};
