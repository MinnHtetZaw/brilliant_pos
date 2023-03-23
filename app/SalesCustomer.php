<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesCustomer extends Model{
    //use SoftDeletes;
    
    protected $table = "sales_customers";
    
    protected $guarded = [];
    
    protected $fillable = [
            'name',
            'phone',
            'credit_amount',
            'paid_status'
        ];
        
    public function User(){
        return $this->belongsTo('App\User');
    }
}