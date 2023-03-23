<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'item_code', 
        'item_name', 
        'created_by',
        'customer_console',
        'photo_path',
        'category_id',
        'sub_category_id',
        'deleted_at',
        'unit_name',
        'stock',
        'item_type',
        'selling_price',
        'sku_code',
        'original_code',
        'description',
        'show_web',
        'web_description',
        'brand_name',
        'model_size',
        'reserve_qty',
        'total_sale_qty',
        'purchase_price',
    ];
    
	public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function sub_category() {
        return $this->belongsTo(SubCategory::class);
    }
    public function getStatusAttribute($status) {
        switch ($this->pivot->status) {
            case '0':
                return "Not Purchase";
                break; 
            case '1':
                return "Purchasing";
                break;          
            case '2':
                return "Arrived";
                break;   
            case '4':
                return "Packed";
                break;     
            case '5':
                return "Out of stock";
                break;    
            case '6':
                return "Delivered";
                break;     
            case '7':
                return "Unpacked";
                break;                     
            default:   //3
                return "Instock";
                break;
        }
    }
    public function purchases() {       
        return $this->belongsToMany(Purchase::class);
    }
}