<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSubmission;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Northwestern\SysDev\DynamicForms\Forms\Form;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(int $id)
    {
        // @todo permissions
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
        // @todo permissions
        $submission = $this->find($id);

        // AJAX requests are always drafts, don't need to do any processing
        if ($request->expectsJson()) {
            $submission->data = (string) $request->getContent();
            $submission->save();

            return response()->json([
                'status' => 'Draft saved',
                'updated_at' => $submission->updated_at,
            ]);
        }

        $data = $request->validateDynamicForm(
            $submission->form_version->definition,
            $request->get('submissionValues')
        );

        $submission->data = $data;
        $submission->save();

        $request->session()->flash('status', sprintf('You have saved your application for %s', $submission->application->id));

        return redirect(route('application-discover'));
    }

    public function show(int $id)
    {
        // @todo permissions
        $submission = $this->find($id);
        $program = $submission->form_version->form->program;

        return view('applicant.submission.show')->with([
            'submission' => $submission,
            'organization' => $program->organization,
            'program' => $program,
            'cycle' => $submission->application->cycle,
            'definition' => $submission->form_version->form->published_version->definition,
            'data' => $submission->data ?? '{}',
        ]);
    }

    /**
     * @TODO move to repo? or make it a scope?
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
