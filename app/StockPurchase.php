<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'purchase_number',
    	'supplier_name',
		'total_quantity',
		'total_price',
		'purchase_date',
		'purchase_by',
        'credit_amount',
		'supplier_id',
		'purchase_remark',
	];

	public function item() {
		return $this->belongsToMany('App\Item')->withPivot('id','quantity','price');
	}
	
	public function user(){
        return $this->belongsTo('App\User','purchase_by');
    }
	public function supplier(){
        return $this->belongsTo('App\Supplier','supplier_id');
    }

    public function supplier_credit_list(){
        return $this->hasOne('App\SupplierCreditList');
    }
}
