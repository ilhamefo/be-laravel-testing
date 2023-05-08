<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineOfBusiness extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'name' => 'json',
        'id' => 'string',
    ];
}
