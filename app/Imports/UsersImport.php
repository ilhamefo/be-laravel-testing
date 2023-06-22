<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    public $now;

    public function __construct()
    {
        $this->now = now();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => Hash::make($this->generatePassword()),
            'email_verified_at' => $this->now,
        ]);
    }

    public function rules(): array
    {
        return [
            "email" => 'email|required|unique:users,email',
            "name" => 'required'
        ];
    }

    public function generatePassword($length = 12): string
    {
        $uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        $password = '';

        // Include at least one symbol
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Include at least one uppercase letter
        $password .= $uppercaseLetters[random_int(0, strlen($uppercaseLetters) - 1)];

        // Include at least one lowercase letter
        $password .= $lowercaseLetters[random_int(0, strlen($lowercaseLetters) - 1)];

        // Include at least one number
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];

        $remainingLength = $length - 4; // Subtract 4 for the already included characters

        $characters = $uppercaseLetters . $lowercaseLetters . $numbers . $symbols;
        $characterCount = strlen($characters);

        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $characters[random_int(0, $characterCount - 1)];
        }

        // Shuffle the password characters to randomize their positions
        $password = str_shuffle($password);

        return $password;
    }
}
