<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveredorderVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveredorder_voucher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('deliveredorder_id');
            $table->unsignedInteger('voucher_id');
            $table->tinyInteger('status')->default(0)->comment('0-delivered,1-return');
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
        Schema::dropIfExists('deliveredorder_voucher');
    }
}
