<?php

namespace App\Models;

use Spatie\Permission\Models\Role as LaravelPermissionsRole;

class Role extends LaravelPermissionsRole
{
    protected $casts = [
        'eligible_primary_affiliations' => 'array',
        'eligible_affiliations' => 'array',
    ];
}
