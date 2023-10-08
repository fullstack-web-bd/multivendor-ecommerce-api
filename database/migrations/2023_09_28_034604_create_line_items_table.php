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
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('tax')->default(0);
            $table->unsignedInteger('shipping')->default(0);
            $table->unsignedInteger('grand_total')->default(0);
            $table->unsignedInteger('shop_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_items');
    }
};