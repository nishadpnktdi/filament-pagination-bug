<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
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
            'phone' => fake()->phoneNumber(),
            'alternate_phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'staff_id' => fake()->unique()->randomNumber(),
            'store_id' => StoreFactory::new(),
            'is_freelancer' => fake()->boolean(),
            'is_active' => fake()->boolean(),
        ];
    }

     /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Staff $staff) {

            $user = $staff->user()->create([
                'name' => $staff->first_name . ' ' . $staff->last_name,
                'email' => $staff->email,
                'password' => bcrypt('password'),
            ]);

            $staff->update([
                'user_id' => $user->id,
            ]);

        });
    }
}
