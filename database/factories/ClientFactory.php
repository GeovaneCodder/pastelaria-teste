<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->cellphone(false),
            'birthday' => $this->faker->date,
            'address' => $this->faker->streetAddress,
            'complement' => $this->faker->word,
            'neighborhood' => $this->faker->word,
            'postal_code' => str_ireplace('-', '', $this->faker->postcode),
        ];
    }
}
