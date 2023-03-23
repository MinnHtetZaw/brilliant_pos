<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [

        'subcategory_code','name','category_id'

    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
