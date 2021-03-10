<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Organization;
use App\Models\Program;
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
                    ->hasCycles(1)
            )
            ->create();
    }
}
