<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = [
        
    ];

    protected $table = 'categories';

    public function product()
    {
        return $this->hasMany(\App\Models\Products::class,'category_id','id');
    }
}
