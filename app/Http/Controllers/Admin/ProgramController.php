<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProgramRequest;
use App\Models\FormType;
use App\Models\Organization;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
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
        return view('admin.program.index')->with([
            'programs' => Program::with('organization')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orgs = Organization::orderBy('name')->get();

        return view('admin.program.create')->with([
            'organizations' => $orgs->pluck('name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProgramRequest $request)
    {
        $program = Program::create($request->validated());
        $request->session()->flash('status', 'Program created.');

        return redirect(route('program.show', ['program' => $program->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Program::with(['forms', 'forms.type'])->findOrFail($id);
        $cycles = $program->cycles()->orderBy('opens_at')->get();
        $forms = $program->forms;
        $types_with_forms = [];

        $form_types = FormType::orderBy('name')->get();
        foreach ($form_types as $type) {
            $types_with_forms[] = [
                'type' => $type,
                'form' => $forms->where('type', $type)->first(),
            ];
        }

        return view('admin.program.show')->with([
            'program' => $program,
            'types_with_forms' => $types_with_forms,
            'cycles' => $cycles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
