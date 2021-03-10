<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\FormType;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition()
    {
        return [
            // 'form_type_id' =>
            'program_id' => Program::factory(),
        ];
    }

    public function application()
    {
        return $this->state(function (array $attributes) {
            return [
                'form_type_id' => FormType::firstWhere('slug', FormType::APPLICATION),
            ];
        });
    }
}
