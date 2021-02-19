<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'auth_type' => User::AUTH_TYPE_NETID,
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->companyEmail,
            'email_verified_at' => now(),
            'password' => null,
            'remember_token' => Str::random(10),
            'last_directory_sync_at' => now(),
            'employee_id' => $this->faker->numerify('#######'),
            'phone' => $this->faker->phoneNumber(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'legal_first_name' => $this->faker->optional(0.1)->firstName(),
            'legal_last_name' => $this->faker->optional(0.1)->lastName(),
            'primary_affiliation' => $this->faker->randomElement(['staff', 'faculty', 'student', 'emeritus']),
            'is_outside_sponsor' => false,
            'is_staff' => $this->faker->boolean(),
            'is_faculty' => $this->faker->boolean(),
            'is_student' => $this->faker->boolean(),
            'is_emeritus' => $this->faker->boolean(),
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
