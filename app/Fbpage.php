<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fbpage extends Model
{
    protected $fillable=['name','code','employee_id','logo'];

    public function employee() {
		return $this->belongsTo(Employee::class);
	}
}