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
            $table->double('amount_to_invoice'); //
            $table->double('amount_total'); // 
            $table->double('amount_untaxed'); //
            $table->timestamp('create_date')->default(now()); //
            $table->string('delivery_status'); // -
            $table->string('internal_note_display'); // -
            $table->string('name')->unique(); //
            $table->string('partner_id_contact_address'); //
            $table->string('partner_id_display_name'); //
            $table->string('partner_id_phone'); //
            $table->string('state'); // -
            $table->boolean('x_studio_commission_paid'); //
            $table->string('x_studio_invoice_payment_status'); //-
            $table->string('x_studio_payment_type'); //
            $table->boolean('x_studio_referrer_processed'); //
            $table->string('x_studio_sales_rep_1'); //
            $table->string('x_studio_sales_source'); //
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
