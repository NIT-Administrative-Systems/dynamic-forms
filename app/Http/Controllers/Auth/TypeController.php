<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function login()
    {
        return view('auth.login-type');
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return redirect(RouteServiceProvider::HOME);
        }

        throw new \Exception('logout redirect NYI');
    }
}
