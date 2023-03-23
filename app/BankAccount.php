<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    //
    protected $guarded = [];
        
    protected $fillable = [
        
        'account_number','opeing_date','account_holder_name','balance','bank_name','bank_address','bank_contact'
        
    ];
    
}
