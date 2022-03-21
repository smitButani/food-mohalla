<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizeOption extends Model
{
    protected $fillable = [
        
    ];

    protected $table = 'product_customize_option';
    
    public function productCustomizeType()
    {
        return $this->hasMany(\App\Models\productCustomizeType::class);
    }
}
