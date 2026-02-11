<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('code', 50)->unique()->nullable()->after('value');
            $table->integer('usage_limit')->nullable()->after('code');
            $table->integer('used_count')->default(0)->after('usage_limit');
            $table->decimal('minimum_amount', 10, 2)->nullable()->after('used_count');
            $table->json('applicable_products')->nullable()->after('is_active');
            $table->json('applicable_categories')->nullable()->after('applicable_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['description', 'code', 'usage_limit', 'used_count', 'minimum_amount', 'applicable_products', 'applicable_categories']);
        });
    }
}
