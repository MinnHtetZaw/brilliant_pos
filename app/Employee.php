<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
	
    protected $guarded = [];

    protected $fillable = [
        'phone','user_id'
    ];

    public function user(){
    	
        return $this->belongsTo('App\User');
    }
    public function fbpages(){
    	
        return $this->hasMany('App\Fbpage');
    }
}
