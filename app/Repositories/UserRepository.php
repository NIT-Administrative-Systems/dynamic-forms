<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findByNetid(string $netid): ?User
    {
        return User::where('auth_type', User::AUTH_TYPE_NETID)
                   ->where('username', strtolower($netid))
                   ->first();
    }
}
