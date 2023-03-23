<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseIdToItemVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_voucher', function (Blueprint $table) {
            $table->unsignedInteger('purchase_id')->default(0)->nullable();
            $table->unsignedInteger('arriveditem_id')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_voucher', function (Blueprint $table) {
            //
        });
    }
}
