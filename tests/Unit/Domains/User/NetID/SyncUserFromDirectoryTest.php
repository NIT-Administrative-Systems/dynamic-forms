<?php

namespace Tests\Unit\Domains\User\NetID;

use App\Domains\User\NetID\SyncUserFromDirectory;
use App\Models\User;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Domains\User\NetID\SyncUserFromDirectory
 */
class SyncUserFromDirectoryTest extends TestCase
{
    /**
     * @dataProvider dpDirectoryData
     *
     * @covers ::__invoke
     * @covers ::affiliations
     * @covers ::demographics
     * @covers ::findValue
     */
    public function testSync(array $directoryData, array $expectedUserAttributes): void
    {
        $sync = new SyncUserFromDirectory;
        $user = $sync(new User, $directoryData);

        // Serialize the model to an array -> pluck the keys we want to compare
        $userActual = Arr::only($user->toArray(), array_keys($expectedUserAttributes));
        $this->assertEquals($userActual, $expectedUserAttributes);
    }

    /**
     * @coversNothing
     */
    public function testPostdoc(): void
    {
        $this->markTestIncomplete('Need postdoc data in dpDirectoryData, but I do not have a netID set up that way to look at');
    }

    /**
     * This data is generated off actual DirectorySearch results, with names/phones/emplIDs manually redacted.
     *
     * Here's the tinker script that gets you most of the way:
     *
     *   $ds = resolve(\Northwestern\SysDev\SOA\DirectorySearch::class);
     *   $keys = ['uid','eduPersonPrimaryAffiliation','eduPersonAffiliation','nuAllSchoolAffiliations','givenName','sn','nuLegalGivenName','nuLegalSn','employeeNumber','mail','telephoneNumber','nuStudentGivenName','nuStudentSn','nuStudentLegalGivenName','nuStudentLegalSn','nuStudentNumber','nuStudentEmail','nuAllStudentCurrentPhone'];
     *   Arr::only($ds->lookupByNetId('aveis', 'basic'), $keys);
     *
     * The netIDs for the test users (uid) have been retained so it's easy to figure out how to regenerate these.
     * However, be mindful of updates: just because somebody was a student today, does not mean they will be in five years!
     */
    public function dpDirectoryData(): array
    {
        return [
            'Staff, with prev. student info' => [
                [
                    "nuAllSchoolAffiliations" => ["staff", "student"],
                    "eduPersonPrimaryAffiliation" => "staff",
                    "givenName" => [
                        "Staff",
                    ],
                    "sn" => [
                        "FormerStudent",
                    ],
                    "uid" => "cec804",
                    "mail" => "sformerstudent@northwestern.edu",
                    "nuStudentEmail" => "staffFormerStudent20XX@u.northwestern.edu",
                    "telephoneNumber" => "+1 847 111 1111",
                    "eduPersonAffiliation" => [
                        "student",
                        "staff",
                        "employee",
                        "member",
                    ],
                    "nuLegalSn" => "FormerStudent",
                    "nuLegalGivenName" => "Staff",
                    "employeeNumber" => "1111111",
                    "nuStudentNumber" => "9999999",
                    "nuAllStudentCurrentPhone" => [
                        "+1 999 999 9999",
                    ],
                    "nuStudentGivenName" => "Staff",
                    "nuStudentSn" => "FormerStudent",
                    "nuStudentLegalGivenName" => "Staff",
                    "nuStudentLegalSn" => "FormerStudent",
                ],
                [
                    'is_emeritus' => false,
                    'is_student' => true,
                    'is_faculty' => false,
                    'is_staff' => true,
                    'primary_affiliation' => User::AFF_STAFF,
                    'first_name' => 'Staff',
                    'last_name' => 'FormerStudent',
                    'legal_first_name' => 'Staff',
                    'legal_last_name' => 'FormerStudent',
                    'phone' => '+1 847 111 1111',
                    'email' => 'sformerstudent@northwestern.edu',
                    'employee_id' => '1111111',
                ],
            ],
            'Faculty, w/ staff affiliation' => [
                [
                    "nuAllSchoolAffiliations" => ["faculty", "staff"],
                    "eduPersonPrimaryAffiliation" => "faculty",
                    "givenName" => [
                        "Faculty",
                    ],
                    "sn" => [
                        "Member",
                    ],
                    "uid" => "wha510",
                    "mail" => "fmember@northwestern.edu",
                    "nuStudentEmail" => "fmember@u.northwestern.edu",
                    "telephoneNumber" => "+1 847 111 1111",
                    "eduPersonAffiliation" => [
                        "employee",
                        "faculty",
                        "member",
                        "staff",
                    ],
                    "nuLegalSn" => "Member",
                    "nuLegalGivenName" => "Faculty",
                    "employeeNumber" => "1111111",
                    "nuStudentNumber" => "",
                    "nuAllStudentCurrentPhone" => [],
                    "nuStudentGivenName" => "",
                    "nuStudentSn" => "",
                    "nuStudentLegalGivenName" => "",
                    "nuStudentLegalSn" => "",
                ],
                [
                    'is_emeritus' => false,
                    'is_student' => false,
                    'is_faculty' => true,
                    'is_staff' => true,
                    'primary_affiliation' => User::AFF_FACULTY,
                    'first_name' => 'Faculty',
                    'last_name' => 'Member',
                    'legal_first_name' => 'Faculty',
                    'legal_last_name' => 'Member',
                    'phone' => '+1 847 111 1111',
                    'email' => 'fmember@northwestern.edu',
                    'employee_id' => '1111111',
                ],
            ],
            'Undergrad student w/ different given vs legal given name' => [
                [
                    "nuAllSchoolAffiliations" => ["student"],
                    "eduPersonPrimaryAffiliation" => "student",
                    "givenName" => [
                        "Studie",
                    ],
                    "sn" => [
                        "NU",
                    ],
                    "uid" => "aln3920",
                    "mail" => "Student.NU20XX@u.northwestern.edu",
                    "nuStudentEmail" => "StudieNU20XX@u.northwestern.edu",
                    "telephoneNumber" => "",
                    "eduPersonAffiliation" => [
                        "employee",
                        "member",
                        "student",
                    ],
                    "nuLegalSn" => "",
                    "nuLegalGivenName" => "",
                    "employeeNumber" => "9999999",
                    "nuStudentNumber" => "1111111",
                    "nuAllStudentCurrentPhone" => [
                        "+1 573 111 1111",
                    ],
                    "nuStudentGivenName" => "Studie",
                    "nuStudentSn" => "NU",
                    "nuStudentLegalGivenName" => "Student",
                    "nuStudentLegalSn" => "NU",
                ],
                [
                    'is_emeritus' => false,
                    'is_student' => true,
                    'is_faculty' => false,
                    'is_staff' => false,
                    'primary_affiliation' => User::AFF_STUDENT,
                    'first_name' => 'Studie',
                    'last_name' => 'NU',
                    'legal_first_name' => 'Student',
                    'legal_last_name' => 'NU',
                    'phone' => '+1 573 111 1111',
                    'email' => 'StudieNU20XX@u.northwestern.edu',
                    'employee_id' => '1111111',
                ],
            ],
            'Retired (emeritus) faculty' => [
                [
                    "nuAllSchoolAffiliations" => ["emeritus"],
                    "eduPersonPrimaryAffiliation" => "employee",
                    "givenName" => [
                        "Emeritus",
                    ],
                    "sn" => [
                        "Faculty",
                    ],
                    "uid" => "aveis",
                    "mail" => "efac@northwestern.edu",
                    "nuStudentEmail" => "",
                    "telephoneNumber" => "+1 312 111 1111",
                    "eduPersonAffiliation" => [
                        "employee",
                        "medschool",
                        "member",
                    ],
                    "nuLegalSn" => "Faculty",
                    "nuLegalGivenName" => "Emeritus",
                    "employeeNumber" => "1111111",
                    "nuStudentNumber" => "",
                    "nuAllStudentCurrentPhone" => [],
                    "nuStudentGivenName" => "",
                    "nuStudentSn" => "",
                    "nuStudentLegalGivenName" => "",
                    "nuStudentLegalSn" => "",
                ],
                [
                    'is_emeritus' => true,
                    'is_student' => false,
                    'is_faculty' => true,
                    'is_staff' => false,
                    'primary_affiliation' => User::AFF_FACULTY,
                    'first_name' => 'Emeritus',
                    'last_name' => 'Faculty',
                    'legal_first_name' => 'Emeritus',
                    'legal_last_name' => 'Faculty',
                    'phone' => '+1 312 111 1111',
                    'email' => 'efac@northwestern.edu',
                    'employee_id' => '1111111',
                ],
            ],
        ];
    }
}
