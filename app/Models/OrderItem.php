<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        
    ];

    protected $table = 'order_item';

    public function product()
    {
        return $this->hasOne(\App\Models\Products::class,'id','product_id');
    }
}
