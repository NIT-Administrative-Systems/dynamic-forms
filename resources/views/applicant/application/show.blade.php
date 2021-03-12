@extends('northwestern::purple-container')

@section('heading')
<h2>Application #{{ $app->id }} for {{ $app->cycle->program->name }}</h2>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <h4>Workflow</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Key</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="text-center"><em>We don't have a workflow system yet.</em></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <h4>Forms</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Form</th>
                    <th scope="col">Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($app->submissions as $submission)
                <tr>
                    <td><a href="{{ route('applicant.submission.show', ['submission' => $submission]) }}">{{ $submission->form_version->form->type->name }}</a></td>
                    <td>{{ $submission->updated_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</table>
@endsection
