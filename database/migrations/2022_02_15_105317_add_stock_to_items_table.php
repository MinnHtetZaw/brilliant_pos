<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('stock')->default(0);
            $table->integer('item_type')->comment('0-instock, 1- preorder');
            $table->integer('selling_price');
            $table->text('sku_code');
            $table->text('original_code')->nullable();
            $table->text('description')->nullable();
            $table->integer('show_web')->default(0)->comment('0-not show, 1- show');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
