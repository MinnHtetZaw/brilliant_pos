<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{

	use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [

    	'voucher_code',
        'order_date',
        'order_type',
        'location_flag',
        'page_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'payment_type',
        'prepaid_clear_flash',
        'total_quantity',
        'item_charges',
        'delivery_charges',
        'total_charges',
        'status',
        'prepaid_amount',
        'collect_amount',
        'delivery_id',
        'sale_by',
        'delivery_expense',
    	'deleted_at',
        'notify_flag',
        'status_change_date'
    ];

    // public function counting_unit() {
    //     return $this->belongsToMany(CountingUnit::class)->withPivot('quantity','price','discount');
    // }
    public function items() {
        return $this->belongsToMany(Item::class)->withPivot('id','quantity','price','status','remark');
    }

    public function user()
    {
        return $this->belongsTo('App\User','sale_by');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function fbpage() {
        return $this->belongsTo('App\Fbpage','page_id');
    }
    // public function sale_customer() {
	// 	return $this->belongsTo('App\SalesCustomer','sales_customer_id');
	// }

    // public function getCreatedAtAttribute($date) {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i A');
    // }
    public function getStatusAttribute($status) {
        switch ($status) {
            case '0':
                return "Order Save";
                break;
            case '1':
                return "Purchasing";
                break;
            case '2':
                return "Arrived";
                break;
            case '3':
                return "Delivered";
                break;
            case '4':
                return "Packed";
                break;
            case '5':
                return "Out of stock";
                break;
            case '6':
                return "Return";
                break;
            case '7':
                return "Unpacked";
                break;
        }
    }
    public function getOrderTypeAttribute($status) {
        switch ($status) {
            case '0':
                return "Instock";
                break;
            default:
                return "PreOrder";
                break;
        }
    }
    public function getNotifyFlagAttribute($status) {
        switch ($status) {
            case '0':
                return "not";
                break;
            default:
                return "has";
                break;
        }
    }
    public function purchases() {
		return $this->belongsToMany('App\Purchase')->withPivot('id','item_id','quantity','status');
	}
    public function deliveryorders() {
		return $this->belongsToMany('App\Deliveredorder')->withPivot('id','status','date','return_remark');
	}
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
