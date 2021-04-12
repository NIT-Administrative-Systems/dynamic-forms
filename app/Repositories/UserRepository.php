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
     * Saves a user and resets their primary affiliation-based role, and optionally other roles.
     *
     * If the $otherRoles array is passed, it will RESET the non-system-assigned roles to that list.
     * This is not *adding* -- it's resetting to the specified list, removing any manually-assigned
     * roles that are not in the list.
     *
     * When the $otherRoles param is null, this DOES NOT touch roles that have been manually assigned,
     * like platform admin or organization admin roles.
     *
     * @see SystemRole::resetableRoles() for what roles are resetable.
     *
     * @param string[] $roles Role names
     */
    public function saveWithPrimaryAffiliationRole(User $user, ?string $affiliationBasedRole, ?array $otherRoles = null): User
    {
        return DB::transaction(function () use ($user, $affiliationBasedRole, $otherRoles) {
            $resetableRoles = collect(SystemRole::resetableRoles())->reject(fn ($role) => $role === $affiliationBasedRole);

            $user->save();
            $resetableRoles->each(fn ($role) => $user->removeRole($role));

            if ($affiliationBasedRole) {
                $user->assignRole($affiliationBasedRole);
            }

            if (is_array($otherRoles)) {
                $user->roles
                    ->reject(fn ($role) => $role->name === $affiliationBasedRole)
                    ->each(fn ($role) => $user->removeRole($role));

                collect($otherRoles)->each(fn ($role) => $user->assignRole($role));
            }

            return $user->refresh();
        });
    }
}
