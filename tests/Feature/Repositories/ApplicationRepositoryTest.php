<?php

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Repositories\ApplicationRepository;
use Tests\MakesOrganizationConfig;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\ApplicationRepository
 */
class ApplicationRepositoryTest extends TestCase
{
    use MakesOrganizationConfig;

    /**
     * @covers ::create
     */
    public function testCreate(): void
    {
        $applicant = User::factory()->create();
        $cycle = $this->makeCycle();

        $app = $this->repo()->create($applicant, $cycle);
        $this->assertEquals(1, $app->submissions->count());
    }

    /**
     * Returns a fresh repository object
     */
    private function repo(): ApplicationRepository
    {
        return new ApplicationRepository;
    }
}
