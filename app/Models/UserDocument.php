<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'url',
        'status',
        'failed_reason'
    ];

    protected $casts = [
        'id' => 'string',
        'failed_reason' => 'json'
    ];

    protected $primaryKey = "id";

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
