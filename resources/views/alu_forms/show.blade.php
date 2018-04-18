@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
        <h1>ALU Form</strong></h1>
        </div>
        <div class="col-6"><span class="float-right">
            <a href="{{URL::previous()}}" class="btn btn-outline-info btn-md">Back</a>
        </span></div>
    </div>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-8">
                {!! Form::open(['action' => "AluFormsController@index", 'method' => 'POST']) !!}
                <div class="form-group row">
                    {{Form::label('name', 'Name', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-4">
                        {{Form::text('name', $user->last_name . ", " . $user->first_name . " " . $user->middle_name, ['readonly', 'class' => 'form-control'])}}
                    </div>
                    {{Form::label('employee_num', 'Emp. No.', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-4">
                        {{Form::number('employee_num', $user->employee_num, ['readonly', 'class' => 'form-control'])}}
                    </div>
                </div>
                <div class="form-group row">
                    {{Form::label('date_filed', 'Date Filed', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::date('date_filed', $alu_form->date_filed, ['readonly', 'class' => 'form-control'])}}
                    </div>
                </div>
                <div class="form-group row">
                    {{Form::label('type', 'Type', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::select('type', getAluTypes(), $alu_form->alu->type, ['disabled', 'class' => 'form-control'])}}                        
                    </div>
                </div>
                <div class="form-group row">
                    {{Form::label('date', 'Date', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-4">
                        {{Form::date('date', $alu->date, ['readonly', 'class' => 'form-control'])}}
                    </div>
                    {{Form::label('time', 'Time', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-4">
                        @if($alu->type == 'A' || $alu->type == 'NT')
                            {{Form::time('time', '', ['readonly', 'class' => 'form-control'])}}
                        @else
                            {{Form::time('time', $alu->time, ['readonly', 'class' => 'form-control'])}}
                        @endif
                    </div>
                </div>   
                <div class="form-group row">
                    {{Form::label('reason', 'Reason/s', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::textarea('reason', $alu_form->reason, ['readonly', 'class' => 'form-control', 'rows' => 5])}}
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card bg-light text-dark">
                    <div class="card-body">
                        {{Form::label('decision', 'Decision', ['class' => 'col-form-label'])}}
                        @if($alu_form->alu->decision)
                            {{Form::select('decision', getDecisionChoices(), $alu_form->alu->decision, ['disabled', 'class' => 'form-control'])}}
                        @else
                            {{Form::text('decision', 'N/A', ['readonly', 'class' => 'form-control'])}}                        
                        @endif

                        <hr>

                        {{Form::label('supervisor_remarks', 'Supervisor Remarks', ['class' => 'col-form-label'])}}
                        {{Form::textarea('supervisor_remarks', ($alu_form->supervisor_remarks) ? $alu_form->supervisor_remarks : 'N/A', ['readonly', 'class' => 'form-control', 'rows' => 2])}}
                        {{Form::label('admin_remarks', 'Personnel Remarks', ['class' => 'col-form-label'])}}
                        {{Form::textarea('admin_remarks', ($alu_form->admin_remarks) ? $alu_form->admin_remarks : 'N/A', ['readonly', 'class' => 'form-control', 'rows' => 2])}}
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection