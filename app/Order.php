<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
	
    protected $guarded = [];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];

    protected $fillable = [ 
       'order_number','address','name','phone','order_date','delivered_date','total_quantity','est_price','status','customer_id','employee_id','deleted_at'
    ];

    public function counting_unit() {
		return $this->belongsToMany('App\CountingUnit')->withPivot('id','quantity');
	}

	public function customer() {

		return $this->belongsTo('App\Customer');
	}

	public function employee() {

		return $this->belongsTo('App\Employee');
	}
}
