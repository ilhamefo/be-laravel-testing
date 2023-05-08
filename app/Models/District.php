<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
    ];

    public function cityDetails()
    {
      return $this->hasOne(City::class, "id", "city_id");
    }
}
