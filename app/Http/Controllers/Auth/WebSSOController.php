<?php

namespace App\Http\Controllers\Auth;

use App\Domains\User\NetID\SyncUserFromDirectory;
use App\Exceptions\ServiceDownError;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Northwestern\SysDev\SOA\Auth\WebSSOAuthentication;
use Northwestern\SysDev\SOA\DirectorySearch;

class WebSSOController extends Controller
{
    use WebSSOAuthentication;

    public function __construct()
    {
        $this->login_route_name = 'login-sso';
        $this->logout_route_name = 'logout-sso';
    }

    protected function findUserByNetID(UserRepository $repo, DirectorySearch $directoryApi, SyncUserFromDirectory $netidSync, string $netid): ?Authenticatable
    {
        // @TODO I don't think laravel-soa does this ... it should!
        $netid = strtolower($netid);

        $user = $repo->findByNetid($netid);

        $directoryData = $directoryApi->lookupByNetId($netid);
        throw_unless($directoryData, new ServiceDownError(ServiceDownError::API_DIRECTORY_SEARCH));

        $user = $netidSync($user, $directoryData);

        return $repo->saveWithRoles($user, []);
    }

    /*
    protected function authenticated(Request $request, $user)
    {
        // Post-authentication hook. You are not required to implement anything here.

        // If you want, you can return a redirect() here & it will be respected.
    }
    */
}
