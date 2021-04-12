<?php

namespace App\Domains\User\ACL;

use App\Models\User;
use Illuminate\Support\Arr;

class SystemRole
{
    const PLATFORM_ADMINISTRATOR = 'platform-administrators';
    const STUDENT = 'students';
    const SPONSOR = 'sponsors';

    const AFF_ROLE_MAP = [
        User::AFF_FACULTY => self::SPONSOR,
        User::AFF_EMERITUS => self::SPONSOR,
        User::AFF_OUTSIDE_SPONSOR => self::SPONSOR,
        User::AFF_STUDENT => self::STUDENT,
    ];

    public static function forPrimaryAffiliation(string $affiliation): ?string
    {
        return Arr::get(self::AFF_ROLE_MAP, $affiliation);
    }

    /**
     * Names of all system-managed roles.
     *
     * @return string[]
     */
    public static function allSystemRoles(): array
    {
        return [
            self::PLATFORM_ADMINISTRATOR,
            self::STUDENT,
            self::SPONSOR,
        ];
    }

    /**
     * Names of roles that the system can reset when logging in.
     *
     * @return string[]
     */
    public static function resetableRoles(): array
    {
        return [
            self::STUDENT,
            self::SPONSOR,
        ];
    }
}
