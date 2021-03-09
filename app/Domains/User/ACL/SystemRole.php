<?php

namespace App\Domains\User\ACL;

use App\Models\User;
use Illuminate\Support\Arr;

class SystemRole
{
    const PLATFORM_ADMINISTRATOR = 'platform-administrators';
    const STUDENT = 'students';
    const SPONSORS = 'sponsors';
    const NONE = 'no-role';

    const AFF_ROLE_MAP = [
        User::AFF_FACULTY => self::SPONSORS,
        User::AFF_EMERITUS => self::SPONSORS,
        User::AFF_STUDENT => self::STUDENT,
    ];

    public static function forPrimaryAffiliation(string $affiliation): string
    {
        return Arr::get(self::AFF_ROLE_MAP, $affiliation, self::NONE);
    }

    public static function resetableRoles(): array
    {
        return [
            self::STUDENT,
            self::SPONSORS,
            self::NONE,
        ];
    }
}
