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
            $table->string('display_name');
            $table->double('categ_name'); //
            $table->double('avg_cost');
            $table->double('total_value');
            $table->double('qty_available');
            $table->double('free_qty');
            $table->double('incoming_qty');
            $table->double('outgoing_qty');
            $table->double('virtual_available');
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
