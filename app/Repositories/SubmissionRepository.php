<?php

namespace App\Repositories;

use App\Models\ApplicationSubmission;
use App\Models\ProgramCycle;
use App\Models\User;
use Illuminate\Support\Collection;

class SubmissionRepository
{
    /**
     * @return Collection<ApplicationSubmission>
     */
    public function findByCycleAndType(User $user, ProgramCycle $cycle, string $form_type): Collection
    {
        return ApplicationSubmission::select('application_submissions.*')
                ->join('applications', 'application_submissions.application_id', '=', 'applications.id')
                ->join('form_versions', 'application_submissions.form_version_id', '=', 'form_versions.id')
                ->join('forms', 'form_versions.form_id', '=', 'forms.id')
                ->join('form_types', 'forms.form_type_id', '=', 'form_types.id')
                ->where('applications.program_cycle_id', $cycle->id)
                ->where('applications.applicant_user_id', $user->id)
                ->where('form_types.slug', $form_type)
                ->get();
    }
}
