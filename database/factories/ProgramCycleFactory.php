<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\ProgramCycle;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramCycleFactory extends Factory
{
    protected $model = ProgramCycle::class;

    public function definition()
    {
        $start = $this->faker->dateTimeThisYear();
        $duration = sprintf('P%sD', $this->faker->numberBetween(20, 180));

        return [
            'program_id' => Program::factory(),
            'opens_at' => DateTimeImmutable::createFromMutable($start),
            'closes_at' => $start->add(new DateInterval($duration)),
        ];
    }
}
