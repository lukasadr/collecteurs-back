<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->name,
            'role_id' => 2,
            'adresse' => $this->faker->address,
            'tel' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'societe' => $this->faker->word,
            'notes' => $this->faker->word,
            'solde' => 2000,
            'type' => $this->faker->randomElement(['normal', 'privilegie', 'vip']),
            'nifstat' => $this->faker->randomNumber,
            'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
