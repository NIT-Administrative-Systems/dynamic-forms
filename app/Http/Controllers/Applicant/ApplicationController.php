<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\FormType;
use App\Models\ProgramCycle;
use App\Repositories\ApplicationRepository;
use App\Repositories\SubmissionRepository;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

    /**
     * responsible for lots of things (TODO):.
     *
     *  - eligibility (is cycle open, right role, etc)
     *  - making an Application row
     *  - preparing an application form entry (defaulting their name/email/etc where applicable)
     *  - confirming they don't already have an open application
     *      - if the cycle allows multiple submissions, asking them if they wanted to start a new app or continue the old one
     *      - detecting if they have an Application already & sending them to the edit (if only 1 app per cycle allowed)
     */
    public function create(Request $request, ProgramCycle $cycle, ApplicationRepository $app_repo, SubmissionRepository $subs_repo)
    {
        // See if the user has any submissions for this cycle
        $cycleAllowsMultipleApplications = false; // @TODO make this an actual setting.
        $other_submissions = $subs_repo->findByCycleAndType($request->user(), $cycle, FormType::APPLICATION);

        if ($other_submissions->count() > 0) {
            if (! $cycleAllowsMultipleApplications) {
                return redirect(route('applicant.submission.edit', [
                    'submission' => $other_submissions->first(),
                ]));
            } else {
                throw new \Exception('NYI: prompt to confirm if you want to make a new app (cycle allows multiple apps)');
            }
        }

        if (! $cycle->isOpen()) {
            // @TODO
            throw new \Exception('this cycle is not open');
        }

        $app = $app_repo->create($request->user(), $cycle);

        return redirect(route('applicant.submission.edit', [
            'submission' => $app->submissions->first(),
        ]));
    }
}
