<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * Finds a user by the netID, or returns a fresh User stub if they do not yet exist
     */
    public function findByNetid(string $netid): User
    {
        $user = User::where('auth_type', User::AUTH_TYPE_NETID)
                    ->where('username', strtolower($netid))
                    ->first();

        return $user ?: new User([
            'username' => $netid,
            'auth_type' => User::AUTH_TYPE_NETID,
        ]);
    }

    public function saveWithRoles(User $user, array $roles = []): User
    {
        return DB::transaction(function () use ($user, $roles) {
            $user->save();
            $user->syncRoles($roles);

            return $user->refresh();
        });
    }
}
