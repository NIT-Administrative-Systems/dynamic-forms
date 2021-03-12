<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $apps = Application::with('cycle.program.organization')
            ->where('applicant_user_id', $request->user()->id)
            ->orderBy('updated_at')
            ->get();

        return view('applicant.application.index')->with([
            'apps' => $apps,
        ]);
    }

    public function show(int $id)
    {
        $app = Application::findOrFail($id);

        return view('applicant.application.show')->with([
            'app' => $app,
        ]);
    }
}
