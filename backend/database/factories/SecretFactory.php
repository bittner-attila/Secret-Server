<?php

namespace Database\Factories;

use App\Models\Secret;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecretFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Secret::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $createdAt = $this->faker->dateTime();
        
        $expiresAtMin = $this->faker->numberBetween(0, 60);
        $expiresAt > 0 ? $this->faker->dateTimeBetween($createdAt, '+' . $expiresAtMin . 'min') : null;

        return [
            'hash' => $this->faker->name(),
            'secretText'     => $this->faker->realText(200, 2),
            'expiresAt'      => $expiresAt,
            'remainingViews' => $this->faker->randomDigit(),
            'createdAt'      => $createdAt,
        ];
    }
}
