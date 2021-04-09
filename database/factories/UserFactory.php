<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
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

    public function localAuth(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'auth_type' => User::AUTH_TYPE_LOCAL,
                'email' => $this->faker->unique()->companyEmail,
                'password' => Hash::make($this->faker->password()),
                'last_directory_sync_at' => null,
                'employee_id' => null,
                'phone' => null,
                'legal_first_name' => null,
                'legal_last_name' => null,
                'primary_affiliation' => User::AFF_OUTSIDE_SPONSOR,
                'is_outside_sponsor' => true,
                'is_staff' => false,
                'is_faculty' => $this->faker->boolean(),
                'is_student' => $this->faker->boolean(),
                'is_emeritus' => $this->faker->boolean(),
            ];
        });
    }

    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
