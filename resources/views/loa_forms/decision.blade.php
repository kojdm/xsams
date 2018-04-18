@extends('layouts.app')

@section('content')
    <h1>LOA Form</h1>
    <hr>
    <div class="container">
        @auth('admin')
            @include('forms.loa-form-dec-rec', ['action' => 'AdminLoaFormsController@loaFormUpdate'])
        @else
            @include('forms.loa-form-dec-rec', ['action' => 'SupervisorController@loaFormUpdate'])            
        @endauth
    </div>
@endsection