@extends('northwestern::purple-container')

@section('heading')
    <h1>Update User</h1>
@endsection

@section('content')
    <livewire:user-form :search="$netid" />
@endsection
