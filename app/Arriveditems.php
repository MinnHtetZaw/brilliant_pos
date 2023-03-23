<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arriveditems extends Model
{
    protected $guarded = [];

    protected $fillable = [
		'total_quantity',
		'arrived_date',
		'arrived_by',
	];


	public function user(){
        return $this->belongsTo('App\User','arrived_by');
    }
}
