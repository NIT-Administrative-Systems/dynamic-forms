<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCycle;
use Illuminate\Http\Request;

class StartApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirects the nice SEO-y "Apply for $PROGRAM" URL to the actual create controllers.
     */
    public function __invoke(Request $request, Organization $organization, Program $program, ProgramCycle $cycle)
    {
        // 404 if the org/prog doesn't match up with the cycle
        abort_if($cycle->program != $program || $cycle->program->organization != $organization, 404);

        return redirect(route('applicant.application.create', ['cycle' => $cycle]));
    }
}
