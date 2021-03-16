<?php

namespace Tests\Feature\Repositories;

use App\Models\Application;
use App\Models\ApplicationSubmission;
use App\Models\FormType;
use App\Models\User;
use App\Repositories\SubmissionRepository;
use Tests\MakesOrganizationConfig;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\SubmissionRepository
 */
class SubmissionRepositoryTest extends TestCase
{
    use MakesOrganizationConfig;

    /**
     * @covers ::findByCycleAndType
     */
    public function testFindByCycleAndType(): void
    {
        $applicant = User::factory()->create();
        $cycle = $this->makeCycle();
        Application::factory()
            ->state([
                'program_cycle_id' => $cycle->id,
                'applicant_user_id' => $applicant->id,
            ])
            ->has(ApplicationSubmission::factory()->count(1), 'submissions')
            ->create();

        $submissions = $this->repo()->findByCycleAndType($applicant, $cycle, FormType::APPLICATION);
        $this->assertCount(1, $submissions);
        $this->assertInstanceOf(ApplicationSubmission::class, $submissions->first());
        $this->assertTrue($submissions->first()->exists);
    }

    public function testFindByCycleAndTypeForNone(): void
    {
        $applicant = User::factory()->create();
        $cycle = $this->makeCycle();

        $submissions = $this->repo()->findByCycleAndType($applicant, $cycle, FormType::APPLICATION);
        $this->assertCount(0, $submissions);
    }

    /**
     * Returns a fresh repository object
     */
    private function repo(): SubmissionRepository
    {
        return new SubmissionRepository;
    }
}
