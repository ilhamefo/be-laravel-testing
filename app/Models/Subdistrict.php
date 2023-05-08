<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subdistrict extends Model
{
  use HasFactory;

  protected $table = 'sub_districts';

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, "id", "subdistrict");
  }

  protected $casts = [
    'id' => 'string',
  ];

  public function districtDetails()
  {
    return $this->hasOne(District::class, "id", "district_id");
  }
}
