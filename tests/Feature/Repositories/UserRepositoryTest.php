<?php

namespace Repositories;

use App\Domains\User\ACL\SystemRole;
use App\Models\User;
use App\Repositories\UserRepository;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\UserRepository
 */
class UserRepositoryTest extends TestCase
{
    /**
     * @dataProvider findByNetIdProvider
     * @covers ::findByNetid
     */
    public function testFindByNetId(string $netid, bool $exists): void
    {
        // Make a local user w/ same name to validate this method isn't considering them
        User::factory()->localAuth()->create(['username' => $netid]);

        if ($exists) {
            $u = User::factory()->create(['username' => strtolower($netid)]);
        }

        $foundUser = $this->repo()->findByNetid($netid);

        $this->assertNotNull($foundUser);
        $this->assertEquals(User::AUTH_TYPE_NETID, $foundUser->auth_type);
        $this->assertEquals(strtolower($netid), $foundUser->username);
        $this->assertEquals($exists, $foundUser->exists);
    }

    public function findByNetIdProvider(): array
    {
        return [
            // netid, exists
            'normal netid' => ['netid123', false],
            'upcased netid' => ['NETID456', false],
            'already exists' => ['TRACY', true],
        ];
    }

    /**
     * @dataProvider saveWithPrimaryAffiliationRoleProvider
     * @covers ::saveWithPrimaryAffiliationRole
     */
    public function testSaveWithPrimaryAffiliationRole(callable $userCallback, ?string $roleName, ?array $otherRoles, array $expectedRoleNames): void
    {
        $user = $this->repo()->saveWithPrimaryAffiliationRole($userCallback(), $roleName, $otherRoles);

        $this->assertEqualsCanonicalizing($expectedRoleNames, $user->roles->map->name->all());
    }

    public function saveWithPrimaryAffiliationRoleProvider(): array
    {
        $studentAndAdmin = function () {
            /** @var User $student */
            $student = User::factory()->create(['primary_affiliation' => User::AFF_STUDENT]);
            $student->assignRole(SystemRole::STUDENT, SystemRole::PLATFORM_ADMINISTRATOR);

            return $student->refresh();
        };

        return [
            // user closure, aff role, expected role names
            'user with no roles' => [
                fn () => User::factory()->create(),
                null,
                null,
                [],
            ],
            'student swapping to staff' => [
                function () {
                    /** @var User $student */
                    $student = User::factory()->create(['primary_affiliation' => User::AFF_STUDENT]);
                    $student->assignRole(SystemRole::STUDENT);

                    return $student->refresh();
                },
                SystemRole::SPONSOR,
                null,
                [SystemRole::SPONSOR],
            ],
            'swapping only affects swappable roles' => [
                $studentAndAdmin,
                SystemRole::SPONSOR,
                null,
                [SystemRole::PLATFORM_ADMINISTRATOR, SystemRole::SPONSOR],
            ],
            'adding otherRoles works' => [
                fn () => User::factory()->create(),
                SystemRole::SPONSOR,
                [SystemRole::PLATFORM_ADMINISTRATOR],
                [SystemRole::SPONSOR, SystemRole::PLATFORM_ADMINISTRATOR],
            ],
            'removing otherRoles does not reset system role' => [
                $studentAndAdmin,
                SystemRole::STUDENT,
                [],
                [SystemRole::STUDENT],
            ],

        ];
    }

    /**
     * Returns a fresh repository object.
     */
    private function repo(): UserRepository
    {
        return new UserRepository;
    }
}
