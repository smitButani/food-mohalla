<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Products extends Model
{
    use HasApiTokens;

    protected $fillable = [
        
    ];

    protected $table = 'products';

    public function productCustomizeType()
    {
        return $this->hasMany(\App\Models\ProductCustomizeType::class,'product_id','id');
    }
}
