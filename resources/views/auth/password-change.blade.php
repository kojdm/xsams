@extends('layouts.app')

@section('content')
    <h1>Change Password</h1>
    <hr>
    {!! Form::open(['action' => 'ChangePasswordsController@store', 'method' => 'POST']) !!}
        <div class="form-group row">
                <div class="col-sm-2"></div>
                {{Form::label('current_password', 'Current Password', ['class' => 'col-sm-2 col-form-label'])}}
                <div class="col-sm-6">
                    {{Form::password('current_password', ['class' => 'form-control'])}}
                </div>
                <div class="col-sm-2"></div>
        </div>
        <div class="form-group row">
                <div class="col-sm-2"></div>
                {{Form::label('new_password', 'New Password', ['class' => 'col-sm-2 col-form-label'])}}
                <div class="col-sm-6">
                    {{Form::password('new_password', ['class' => 'form-control'])}}
                </div>
                <div class="col-sm-2"></div>
        </div>
        <div class="form-group row">
                <div class="col-sm-2"></div>
                {{Form::label('new_password_confirmation', 'Confirm Password', ['class' => 'col-sm-2 col-form-label'])}}
                <div class="col-sm-6">
                    {{Form::password('new_password_confirmation', ['class' => 'form-control'])}}
                </div>
                <div class="col-sm-2"></div>
        </div>
        <br>
        <div class="form-group row">
            <div class="col-sm-3"></div>
            {{Form::submit('Submit', ['class' => 'btn btn-success col-sm-6'])}}
            <div class="col-sm-3"></div>
        </div>
    {!! Form::close() !!}
@endsection