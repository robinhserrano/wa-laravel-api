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
        Schema::table('landing_prices', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('internal_reference')->nullable()->change();
            $table->string('product_category')->nullable()->change();
            $table->double('installation_service')->nullable()->change();
            $table->double('supply_only')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_prices', function (Blueprint $table) {
            //
        });
    }
};
