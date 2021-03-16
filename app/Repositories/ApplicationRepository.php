<?php

namespace App\Repositories;

use App\Exceptions\InvalidConfigurationError;
use App\Models\Application;
use App\Models\FormType;
use App\Models\ProgramCycle;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApplicationRepository
{
    /**
     * Creates a new Application for a cycle.
     *
     * This includes setting up an empty application form Submission
     */
    public function create(User $applicant, ProgramCycle $cycle): Application
    {
        $form_type = FormType::where('slug', FormType::APPLICATION)->first();
        $form = $cycle->program->forms()->where('form_type_id', $form_type->id)->first();

        throw_unless($form, new InvalidConfigurationError(sprintf('No %s form defined for %s', FormType::APPLICATION, $cycle->program->name)));

        return DB::transaction(function () use ($applicant, $cycle, $form) {
            $app = Application::create([
                'applicant_user_id' => $applicant->id,
                'program_cycle_id' => $cycle->id,
            ]);

            $app->submissions()->create([
                'form_version_id' => $form->published_version->id,
                'user_id' => $applicant->id,
            ]);

            return $app;
        });
    }
}
