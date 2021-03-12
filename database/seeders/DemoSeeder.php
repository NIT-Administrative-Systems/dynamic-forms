<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCycle;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoSeeder extends Seeder
{
    /**
     * Seeder for reasonable-ish demo data.
     *
     * It's not totally random like the TestDataSeeder.
     */
    public function run()
    {
        $this->office(
            ['name' => 'Office of Undergraduate Research', 'slug' => 'office-undergrad-research'],
            [
                ['name' => 'Summer Research Grant', 'slug' => 'surg'],
                ['name' => 'Academic Year Research Grant', 'slug' => 'acyear'],
            ]
        );

        $this->office(
            ['name' => 'Baker Program in Undergraduate Research ', 'slug' => 'baker'],
            [
                ['name' => 'Weinberg Summer Research Grant', 'slug' => 'baker-surg'],
                ['name' => 'Weinberg Conference Travel Grant', 'slug' => 'baker-conf'],
            ]
        );
    }

    private function office(array $organizationAttributes, array $programs): void
    {
        $opens_at = Carbon::now()->subMonth();
        $closes_at = $opens_at->copy()->addYear();

        Organization::factory()
            ->state($organizationAttributes)
            ->has(Program::factory()
                ->state(new Sequence(...$programs))
                ->count(count($programs))
                ->has(Form::factory()
                    ->application()
                    ->count(1)
                    ->hasVersions(1)
                )
                ->has(ProgramCycle::factory()->state(['opens_at' => $opens_at, 'closes_at' => $closes_at])->count(1), 'cycles'),
                'programs'
            )->create();
    }
}
