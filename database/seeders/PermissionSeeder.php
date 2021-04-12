<?php

namespace Database\Seeders;

use App\Domains\User\ACL\SystemPermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        collect([
            ['name' => SystemPermission::VIEW_VAPOR_UI],
        ])->each(fn ($permission) => Permission::create($permission));
    }
}
