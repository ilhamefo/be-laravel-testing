<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';
    protected $fillable = ["transaction_id", "product_id", "quantity", "subtotal"];
    protected $casts = [
        "id" => "string"
    ];

    public function products()
    {
        return $this->hasOne(Product::class, "id", "product_id");
    }
}