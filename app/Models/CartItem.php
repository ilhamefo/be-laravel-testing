<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
class CartItem extends Model
{
    use HasFactory;
    protected $collection = 'cart_items';

    protected $connection = "mongodb";


    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
