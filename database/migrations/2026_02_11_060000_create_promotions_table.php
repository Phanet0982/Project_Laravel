<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'buy_x_get_y', 'free_shipping']);
            $table->decimal('value', 10, 2); // Discount amount or percentage
            $table->string('code', 50)->unique()->nullable(); // Coupon code
            $table->integer('usage_limit')->nullable(); // Maximum number of uses
            $table->integer('used_count')->default(0); // Current usage count
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum purchase amount
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->json('applicable_products')->nullable(); // Array of product IDs or 'all'
            $table->json('applicable_categories')->nullable(); // Array of category IDs or 'all'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}