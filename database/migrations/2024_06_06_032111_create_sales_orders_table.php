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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->double('amount_to_invoice')->nullable(); //
            $table->double('amount_total')->nullable(); // 
            $table->double('amount_untaxed')->nullable(); //
            $table->timestamp('create_date')->default(now()); //
            $table->string('delivery_status')->nullable(); // -
            $table->text('internal_note_display')->nullable(); // -
            $table->string('name')->unique(); //
            $table->string('partner_id_contact_address')->nullable(); //
            $table->string('partner_id_display_name')->nullable(); //
            $table->string('partner_id_phone')->nullable(); //
            $table->string('state')->nullable(); // -
            $table->boolean('x_studio_commission_paid')->nullable(); //
            $table->string('x_studio_invoice_payment_status')->nullable(); //-
            $table->string('x_studio_payment_type')->nullable(); //
            $table->boolean('x_studio_referrer_processed')->nullable(); //
            $table->string('x_studio_sales_rep_1')->nullable(); //
            $table->string('x_studio_sales_source')->nullable(); //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
