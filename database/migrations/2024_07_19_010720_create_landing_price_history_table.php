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
        Schema::create('landing_price_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('landing_price_id'); // Foreign key to landing_prices table
            $table->double('installation_service')->nullable();
            $table->double('supply_only')->nullable();
            $table->timestamp('recorded_at'); // Timestamp of the price record
            $table->foreign('landing_price_id')->references('id')->on('landing_prices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_price_history');
    }
};
