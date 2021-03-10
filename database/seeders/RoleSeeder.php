<?php

namespace Database\Seeders;

use App\Domains\User\ACL\SystemRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        collect([
            [
                'name' => SystemRole::PLATFORM_ADMINISTRATOR,
                'eligible_primary_affiliations' => null,
                'eligible_affiliations' => [User::AFF_STAFF, User::AFF_FACULTY],
            ],
            [
                'name' => SystemRole::SPONSORS,
                'eligible_primary_affiliations' => [User::AFF_FACULTY],
                'eligible_affiliations' => [User::AFF_FACULTY, User::AFF_EMERITUS],
            ],
            [
                'name' => SystemRole::STUDENT,
                'eligible_primary_affiliations' => [User::AFF_STUDENT],
                'eligible_affiliations' => [User::AFF_STUDENT],
            ],
        ])->each(fn (array $role) => Role::create($role));
    }
}
