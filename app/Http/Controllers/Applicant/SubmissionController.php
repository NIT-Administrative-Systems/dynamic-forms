<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\FormVersion;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'form_version_id' => 'required|numeric|exists:form_versions,id',
            'data' => 'required|json',
        ]);

        // @TODO REPO ME
        $form_version = FormVersion::findOrFail($data['form_version_id']);

    }
}
