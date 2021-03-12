<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationSubmission;
use App\Models\Form;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCycle;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        Organization::factory()
            ->count(4)
            ->has(
                Program::factory()
                    ->count(2)
                    ->has(Form::factory()
                        ->application()
                        ->count(1)
                        ->hasVersions(3)
                    )
                    ->has(ProgramCycle::factory()
                        ->count(1)
                        ->has(Application::factory()
                            ->count(10)
                            ->has(ApplicationSubmission::factory()->count(1), 'submissions')
                        ),
                        'cycles'
                    )
            )
            ->create();
    }
}
