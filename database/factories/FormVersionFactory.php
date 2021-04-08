<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\FormVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormVersionFactory extends Factory
{
    protected $model = FormVersion::class;

    public function definition()
    {
        return [
            'form_id' => Form::factory(),
            'published_at' => $this->faker->dateTimeThisYear(),
            'definition' => $this->formio(),
        ];
    }

    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return ['published_at' => null];
        });
    }

    /**
     * Holds a big string of Form.io definition JSON.
     */
    private function formio(): string
    {
        return file_get_contents(database_path('sample_form.json'));
    }
}
