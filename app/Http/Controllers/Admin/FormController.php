<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFormRequest;
use App\Models\Form;
use App\Models\FormType;
use App\Models\FormVersion;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $params = $request->validate([
            'program' => 'required|numeric|exists:programs,id',
            'type' => 'required|numeric|exists:form_types,id',
        ]);

        return view('admin.form.create', [
            'program' => Program::findOrFail($params['program']),
            'form_type' => FormType::findOrFail($params['type']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFormRequest $request)
    {
        $data = $request->validated();

        // @TODO this belongs in a repo lol
        $form = Form::create(Arr::only($data, ['program_id', 'form_type_id']));
        FormVersion::create([
            'form_id' => $form->id,
            'definition' => $data['definition'],
            'published_at' => Carbon::now(),
        ]);

        $request->session()->flash('status', 'The form was created.');

        return redirect(route('admin.program.show', ['program' => $form->program->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = Form::findOrFail($id);

        return view('admin.form.edit')->with([
            'form' => $form,
            'definition' => $form->published_version->definition ?? '{ components: [] }',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'definition' => 'required|json',
        ]);

        // @TODO this belongs in a repo lol
        $form = Form::findOrFail($id);
        FormVersion::create([
            'form_id' => $form->id,
            'definition' => $data['definition'],
            'published_at' => Carbon::now(),
        ]);

        $request->session()->flash('status', 'The form was updated.');

        return redirect(route('admin.program.show', ['program' => $form->program->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
