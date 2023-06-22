<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Product extends Model
{
    use HasFactory, HybridRelations;
    protected $connection = "pgsql";

    protected $fillable = [
        'name',
        'quantity'
    ];

    protected $casts = [
        'id'       => 'string',
        'quantity' => 'int'
    ];

    protected $primaryKey = "id";



}