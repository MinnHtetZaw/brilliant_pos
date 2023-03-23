<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deliveredorder extends Model
{
    protected $fillable = ['delivery_id','date','remark','total_order_qty','total_collect_amount','total_received_amount','total_delivery_expense'];

    public function vouchers() {
		return $this->belongsToMany('App\Voucher')->withPivot('id','status','date','return_remark');
	}
  public function delivery() {
		return $this->belongsTo('App\Delivery');
	}
}