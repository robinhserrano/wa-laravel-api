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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('display_name')->nullable();
            $table->double('categ_name')->nullable();
            $table->double('avg_cost')->nullable();
            $table->double('total_value')->nullable();
            $table->double('qty_available')->nullable();
            $table->double('free_qty')->nullable();
            $table->double('incoming_qty')->nullable();
            $table->double('outgoing_qty')->nullable();
            $table->double('virtual_available')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
