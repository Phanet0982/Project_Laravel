<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('backup_data'); // JSON data of products and stock levels
            $table->integer('product_count');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('backup_date');
            $table->timestamps();
            
            $table->index(['created_by', 'backup_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_backups');
    }
}
