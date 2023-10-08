<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('pending');
            $table->unsignedDecimal('total', 10, 2)->default(0);
            $table->unsignedDecimal('discount', 10, 2)->default(0);
            $table->unsignedDecimal('tax', 10, 2)->default(0);
            $table->unsignedDecimal('shipping', 10, 2)->default(0);
            $table->unsignedDecimal('grand_total', 10, 2)->default(0);
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->string('order_note')->nullable();

            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();

            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();
            $table->string('shipping_carrier')->nullable()->comment('Shipping company name');

            $table->unsignedBigInteger('parent_order_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable()->comment('If there is a parent order id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};