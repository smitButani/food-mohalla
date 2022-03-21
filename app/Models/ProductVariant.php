<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class ProductVariant extends Model
{
    use HasApiTokens;

    protected $fillable = [
        
    ];

    protected $table = 'product_variant';
}
