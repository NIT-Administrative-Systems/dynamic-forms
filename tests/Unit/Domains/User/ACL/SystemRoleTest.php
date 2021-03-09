<?php

namespace Tests\Unit\Domains\User\ACL;

use App\Domains\User\ACL\SystemRole;
use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Domains\User\ACL\SystemRole
 */
class SystemRoleTest extends TestCase
{
    /**
     * @dataProvider dpMappings
     */
    public function testMapping(string $primaryAff, string $expectedRole): void
    {
        $this->assertEquals($expectedRole, SystemRole::forPrimaryAffiliation($primaryAff));
    }

    public function testResetableRoles(): void
    {
        $this->assertCount(3, SystemRole::resetableRoles());
    }

    public function dpMappings(): array
    {
        return [
            'Faculty' => [User::AFF_FACULTY, SystemRole::SPONSORS],
            'Retired faculty' => [User::AFF_EMERITUS, SystemRole::SPONSORS],
            'Student' => [User::AFF_STUDENT, SystemRole::STUDENT],
            'Staff' => [User::AFF_STAFF, SystemRole::NONE],
            'Unknown value' => ['dog', SystemRole::NONE],
        ];
    }
}

