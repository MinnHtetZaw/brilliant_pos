<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_code');
            $table->date('order_date');
            $table->Integer('order_type')->comment('0-instock,1-preorder');
            $table->Integer('location_flag')->comment('0-ygn,1-mdy,2-npt,3-others');
            $table->unsignedInteger('page_id');
            $table->text('customer_name');
            $table->text('customer_phone');
            $table->text('customer_address');
            $table->integer('payment_type')->comment('0-COD,1-prepaid full,2-prepaid partial');
            $table->integer('prepaid_clear_flash')->default(0)->comment('0-not need prepaid or full paid,1-not clear just for others(location-flag)');
            $table->Integer('total_quantity');
            $table->Integer('item_charges');
            $table->Integer('delivery_charges');
            $table->Integer('total_charges');
            $table->tinyInteger('status')->default(0)->comment('0-orderSave,1-itemPurchase,2-arrived Or Packed,3-delivered');
            $table->Integer('prepaid_amount')->default(0);
            $table->Integer('collect_amount')->default(0);
            $table->unsignedInteger('delivery_id');
            $table->Integer('sale_by');
            $table->softDeletes();
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
        Schema::dropIfExists('vouchers');
    }
}
