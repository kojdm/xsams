@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add Employee</h1>
    </div>
    <hr>
    <div class="container">
        @include('forms.user-form')
    </div>
@endsection