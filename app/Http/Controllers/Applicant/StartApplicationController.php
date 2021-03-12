<?php

namespace App\Http\Controllers\Applicant;

use App\Exceptions\InvalidConfigurationError;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationSubmission;
use App\Models\FormType;
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
     * responsible for lots of things (TODO):.
     *
     *  - eligibility (is cycle open, right role, etc)
     *  - making an Application row
     *  - preparing an application form entry (defaulting their name/email/etc where applicable)
     *  - confirming they don't already have an open application
     *      - if the cycle allows multiple submissions, asking them if they wanted to start a new app or continue the old one
     *      - detecting if they have an Application already & sending them to the edit (if only 1 app per cycle allowed)
     */
    public function __invoke(Request $request, Organization $organization, Program $program, ProgramCycle $cycle)
    {
        // 404 if the org/prog doesn't match up with the cycle
        abort_if($cycle->program != $program || $cycle->program->organization != $organization, 404);

        $form_type = FormType::where('slug', FormType::APPLICATION)->first();
        $form = $cycle->program->forms()->where('form_type_id', $form_type->id)->first();
        throw_unless($form, new InvalidConfigurationError("No form. Create an Application Form under Admin -> Programs -> {$cycle->program->name} -> Application Form"));

        if (! $cycle->isOpen()) {
            // @TODO
            throw new \Exception('this cycle is not open');
        }

        // @TODO repo me
        $app = Application::create([
            'applicant_user_id' => $request->user()->id,
            'program_cycle_id' => $cycle->id,
        ]);

        $submission = ApplicationSubmission::create([
            'application_id' => $app->id,
            'form_version_id' => $form->published_version->id,
            'user_id' => $request->user()->id,
        ]);

        return redirect(route('applicant.submission.edit', ['submission' => $submission]));
    }
}
