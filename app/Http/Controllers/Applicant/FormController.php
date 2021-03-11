<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCycle;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request, Organization $organization, Program $program, ProgramCycle $cycle)
    {
        // 404 if the org/prog doesn't match up with the cycle
        abort_if($cycle->program != $program || $cycle->program->organization != $organization, 404);

        $form_type = FormType::where('slug', FormType::APPLICATION)->first();
        $form = $cycle->program->forms()->where('form_type_id', $form_type->id)->first();
        throw_unless($form, new \Exception("No form. Create an Application Form under Admin -> Programs -> {$cycle->program->name} -> Application Form"));

        return view('applicant.application-form')->with([
            'organization' => $cycle->program->organization,
            'program' => $cycle->program,
            'cycle' => $cycle,
            'definition' => $form->published_version->definition,
        ]);
    }
}
