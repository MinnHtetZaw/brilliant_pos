<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Canceledorder extends Model
{
    protected $fillable = ['order_id','date','remark'];

    
  public function voucher() {
		return $this->belongsTo('App\Voucher');
	}
}