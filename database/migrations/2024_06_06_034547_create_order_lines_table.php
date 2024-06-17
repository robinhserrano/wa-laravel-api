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
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->string('product')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('tax_excl')->nullable();
            $table->double('disc')->nullable();
            $table->string('taxes')->nullable();
            $table->boolean('delivered')->nullable();
            $table->boolean('invoiced')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
