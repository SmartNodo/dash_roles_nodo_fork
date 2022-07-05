<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultedCreditFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idState_state' => rand(1, 32),
            'user_id' => 1,
            'creditNumber' => $this->faker->numerify('##########'),
        ];
    }
}