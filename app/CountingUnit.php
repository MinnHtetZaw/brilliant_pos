<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountingUnit extends Model
{
	use SoftDeletes;
	
    protected $guarded = [];

    protected $fillable = [
    	'unit_code',
    	'original_code',
		'unit_name',
		'current_quantity',
		'reorder_quantity',
		'normal_sale_price',
		'whole_sale_price',
		'order_price',
		'purchase_price',
		'item_id',
		'normal_fixed_flash',
		'normal_fixed_percent',
		'whole_fixed_flash',
		'whole_fixed_percent',
		'order_fixed_flash',
		'order_fixed_percent',
	];

	public function item() {
		return $this->belongsTo(Item::class);
	}

	public function order() {
		return $this->belongsToMany('App\Order')->withPivot('id','quantity');
	}
	public function stockcount()
	{
        return $this->hasMany(Stockcount::class);
	}
}
