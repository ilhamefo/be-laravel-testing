<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HybridRelations;

    protected $connection = "pgsql";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'update_phone_at',
        'update_email_at',
        'update_bank_at',
        'update_personal_data_at',
        'update_home_address_at',
        'update_employment_at',
        'update_additional_information_at',
        'birth_date',
        'birth_place',
        'nik',
        'npwp',
        'mother_name',
        'update_employment_at',
        'gender',
        'address',
        'subdistrict',
        'company_address',
        'company_name',
        'company_subdistrict',
        'gross_income_id',
        'job_title_id',
        'line_of_business_id',
        'occupation_id',
        'source_of_fund_free_text',
        'source_of_fund_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sub_district()
    {
        return $this->hasOne(Subdistrict::class, "id", "subdistrict");
    }

    public function companySubdistrict()
    {
        return $this->hasOne(Subdistrict::class, "id", "company_subdistrict");
    }

    public function genderDetails()
    {
        return $this->hasOne(Gender::class, "id", "gender");
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'id');
    }

    public function docs()
    {
        return $this->hasMany(UserDocument::class);
    }
}