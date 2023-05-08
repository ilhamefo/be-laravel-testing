<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
    ];

    public function countryDetails()
    {
      return $this->hasOne(Country::class, "id", "country_id");
    }

}
