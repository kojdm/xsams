@extends('layouts.app')

@section('content')
    <h1>LOA Form</h1>
    <hr>
    <div class="container">
        @include('forms.loa-form-create')
        {{-- @include('forms.loa-form-create-2') --}}
    </div>
@endsection