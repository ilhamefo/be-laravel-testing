<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'     => $this->faker->sentence(5),
            'quantity' => $this->faker->numberBetween(10, 100),
            'price'    => $this->faker->numberBetween(10, 999) * 1000,
            'user_id' => function () {
                return User::inRandomOrder()->first()->id;
            },
        ];
    }

    // public function user(User $user)
    // {
    //     return [
    //         'user_id' => $user->id,
    //     ];
    // }
}