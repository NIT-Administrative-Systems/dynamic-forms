@extends('northwestern::purple-container')

@section('heading')
<h2>{{ $program->name }}</h2>
@endsection

@section('content')
<p>Brief explanation of my intentions here: there will be a finite set of forms that grant admins <em>may</em> choose to define & use. The <code>Application Form</code> will probs always be required, but everything else is only needed if they're going to use it.</p>
<p>We can offer less-common types of forms, but this gives us (the devs) an ability to hook into forms. E.g. we can enforce a yes/no "is this endorsed" question in the <code>Endorsement</code> form with an expected field name. The expected field name is in turn used by the workflow system.</p>
<div class="row">
    <div class="col-12 col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Starts At</th>
                    <th scope="col">Ends At</th>
                    <th scope="col">Active?</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cycles as $cycle)
                    <tr>
                        <td>{{ $cycle->starts_at }}</td>
                        <td>{{ $cycle->ends_at }}</td>
                        <td>{{ $cycle->is_open ? 'Open' : 'Closed' }}</td>
                    </tr>
                @empty
                <tr>
                    <td colspan="3"><em>No cycles have been scheduled</em></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="col-12 col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Form</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($types_with_forms as $pair)
                    <tr>
                        <td>{{ $pair['type']->name }}</td>
                        <td>
                            @if($pair['form'])
                                <a href="{{ route('form.edit', ['form' => $pair['form']->id]) }}">Edit</a>
                            @else
                                <a href="{{ route('form.create', ['program' => $program->id, 'type' => $pair['type']->id]) }}">Create</a>
                            @endif
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="3"><em>No cycles have been scheduled</em></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
