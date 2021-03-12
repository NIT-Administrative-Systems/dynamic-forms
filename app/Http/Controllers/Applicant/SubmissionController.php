<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSubmission;
use App\Models\FormVersion;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(int $id)
    {
        $submission = $this->find($id);
        $program = $submission->form_version->form->program;

        return view('applicant.submission.edit')->with([
            'submission' => $submission,
            'organization' => $program->organization,
            'program' => $program,
            'cycle' => $submission->application->cycle,
            'definition' => $submission->form_version->form->published_version->definition,
            'data' => $submission->data ?? '{}',
        ]);
    }

    public function update(Request $request, int $id)
    {
        $submission = $this->find($id);
        $data = $request->validate([
            'data' => 'required|json',
        ]);

        $submission->data = $data['data'];
        $submission->save();

        $request->session()->flash('status', sprintf('You have saved your application for %s', $submission->application->id));

        return redirect(route('application-discover'));
    }

    /**
     * @TODO move to repo
     */
    private function find(int $id): ApplicationSubmission
    {
        return ApplicationSubmission::with([
            'user',
            'application.applicant',
            'application.cycle',
            'form_version.form.program.organization',
        ])->findOrFail($id);
    }
}
