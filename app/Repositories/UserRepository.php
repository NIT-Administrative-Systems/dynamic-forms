<?php

namespace App\Repositories;

use App\Domains\User\ACL\SystemRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * Finds a user by the netID, or returns a fresh User stub if they do not yet exist.
     */
    public function findByNetid(string $netid): User
    {
        $user = User::where('auth_type', User::AUTH_TYPE_NETID)
                    ->where('username', strtolower($netid))
                    ->first();

        return $user ?: new User([
            'username' => strtolower($netid),
            'auth_type' => User::AUTH_TYPE_NETID,
        ]);
    }

    /**
     * Saves a user and resets their primary affiliation-based role.
     *
     * Any resettable system roles NOT passed will be removed, e.g. if this user
     * has a Student role currently, but the Sponsor role is passed, then Student
     * is replaced with Sponsor.
     *
     * This DOES NOT touch roles that have been manually assigned, like platform admin
     * or organization admin roles.
     *
     * @see SystemRole::resetableRoles() for what roles are resetable.
     *
     * @param string[] $roles Role names
     */
    public function saveWithPrimaryAffiliationRole(User $user, ?string $affiliationBasedRole): User
    {
        return DB::transaction(function () use ($user, $affiliationBasedRole) {
            $resetableRoles = collect(SystemRole::resetableRoles())->reject(fn ($role) => $role === $affiliationBasedRole);

            $user->save();
            $resetableRoles->each(fn ($role) => $user->removeRole($role));

            if ($affiliationBasedRole) {
                $user->assignRole($affiliationBasedRole);
            }

            return $user->refresh();
        });
    }
}
