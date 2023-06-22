<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ["items", "user_id", "cart_items"];
    protected $connection = "mongodb";
    protected $collection = 'carts';

    protected $casts = [
        // 'cart_items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products()
    {
        // return $this->hasMany(Product::class, 'id', 'cart_items.id');
        // return $this->hasMany('App\Models\Product', 'id', 'cart_items');
        // return $this->hasManyThrough(
        //     'App\Models\Product',
        //     'App\Models\CartItem',
        //     'cart_id',
        //     'id',
        //     '_id',
        //     'product_id'
        // );

        // return $this->hasOne(CartItem::class);
    }
}