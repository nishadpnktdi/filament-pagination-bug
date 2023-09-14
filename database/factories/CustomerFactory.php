<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'alternate_phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            // 'user_id' => UserFactory::new(),
            'store_id' => StoreFactory::new(),
            'entry_by_user' => 1,
            'salesman_id' => StaffFactory::new(),
            'register_number' => fake()->unique()->randomNumber(),
            'is_active' => fake()->boolean(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Customer $customer) {

            $user = $customer->user()->create([
                'name' => $customer->first_name . ' ' . $customer->last_name,
                'email' => $customer->email,
                'password' => bcrypt('password'),
            ]);

            $customer->update([
                'user_id' => $user->id
            ]);

        });
    }
}
