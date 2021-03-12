<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationSubmission;
use App\Models\FormVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationSubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApplicationSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'form_version_id' => FormVersion::factory(),
            'application_id' => Application::factory(),
            'data' => json_encode(['foo' => 'bar', 'baz' => 'bat']),
        ];
    }
}
