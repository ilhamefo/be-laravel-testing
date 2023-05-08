<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'name' => 'json',
        'id' => 'string',
        'min_value' => 'double',
        'max_value' => 'double',
    ];
}
