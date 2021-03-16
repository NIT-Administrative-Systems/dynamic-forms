<?php

namespace Tests;

use App\Models\Form;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCycle;
use Illuminate\Support\Carbon;

/**
 * Factory helper methods to produce commonly-needed org/program/form setups
 *
 * This is like an on-demand seeder DemoSeeder for individual tests.
 */
trait MakesOrganizationConfig
{
    /**
     * Makes an office, program, open cycle, and form definitions
     */
    protected function makeCycle(): ProgramCycle
    {
        $opens_at = Carbon::now()->subMonth();
        $closes_at = $opens_at->copy()->addYear();

        $org = Organization::factory()
            ->has(Program::factory()
                ->count(1)
                ->has(Form::factory()
                    ->application()
                    ->count(1)
                    ->hasVersions(1)
                )
                ->has(ProgramCycle::factory()->state(['opens_at' => $opens_at, 'closes_at' => $closes_at])->count(1), 'cycles'),
                'programs'
            )->create();

        return $org->programs->first()->cycles->first();
    }
}
