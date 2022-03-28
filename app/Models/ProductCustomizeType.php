<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizeType extends Model
{
    protected $fillable = [
        
    ];

    protected $table = 'product_customize_types';

    public function productCustomizeOption()
    {
        return $this->hasMany(\App\Models\ProductCustomizeOption::class,'customize_type_id','id');
    }
}
