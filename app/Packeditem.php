<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Packeditem extends Model
{
    protected $guarded = [];

    protected $fillable = [
		'packed_date',
		'packed_by',
        'voucher_id',
	];
    public function packed_by(){
        return $this->belongsTo('App\User','packed_by');
    }
    public function voucher(){
        return $this->belongsTo('App\Voucher','voucher_id');
    }
}
