<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.user.index')->with([
            'users' => User::orderBy('username')->paginate(),
        ]);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit')->with([
            'netid' => $user->username,
        ]);
    }
}
