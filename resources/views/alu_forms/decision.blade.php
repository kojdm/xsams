@extends('layouts.app')

@section('content')
    <h1>ALU Form</h1>
    <hr>
    <div class="container">
        @auth('admin')
            @include('forms.alu-form-dec-rec', ['action' => 'AdminAluFormsController@aluFormUpdate'])
        @else
            @include('forms.alu-form-dec-rec', ['action' => 'SupervisorController@aluFormUpdate'])            
        @endauth
    </div>
@endsection