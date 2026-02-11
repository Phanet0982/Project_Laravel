<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_preferences', function (Blueprint $table) {
            $table->id();
            $table->boolean('low_stock_alerts')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('auto_backup')->default(false);
            $table->boolean('receipt_printing')->default(true);
            $table->integer('low_stock_threshold')->default(10);
            $table->text('receipt_footer_text')->nullable();
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
        Schema::dropIfExists('system_preferences');
    }
}
