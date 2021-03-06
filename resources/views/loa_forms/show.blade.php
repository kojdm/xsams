@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
        <h1>LOA Form</strong></h1>
        </div>
        <div class="col-6"><span class="float-right">
            <a href="{{URL::previous()}}" class="btn btn-outline-info btn-md">Back</a>
        </span></div>
    </div>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-9">
                {!! Form::open(['action' => 'LoaFormsController@store', 'method' => 'POST']) !!}
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
                        {{Form::date('date_filed', $loa_form->date_filed, ['readonly', 'id' => 'date_filed', 'class' => 'form-control'])}}
                    </div>
                </div>
                <div class="form-group row">
                    {{Form::label('type', 'Type of LOA', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::select('type', ['regular' => 'Regular LOA', 'sick' => 'Sick LOA'], $loa_form->type, ['disabled', 'placeholder' => 'Select LOA Type', 'id' => 'loa_type', 'onChange' => "loaClassification(); clearNumDays();", 'class' => 'form-control'])}}
                    </div>
                </div>
                <div class="form-group row">
                    {{Form::label('inclusive_dates', 'Inclusive Dates', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::text('inclusive_dates', $loa_form->inclusiveDates(), ['readonly', 'placeholder' => 'Select inclusive dates for leave', 'id' => 'inclusive_dates_sick', 'onChange' => "showDaysSick()", 'onkeypress' => "return false;", 'class' => 'form-control', 'autocomplete' => 'off'])}}
                    </div>
                </div>
                <div class="form-group row">
                    {{Form::label('num_work_days', 'Number of Days', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::text('num_work_days', count($loa_form->alus), ['readonly', 'id' => 'num_days', 'class' => 'form-control'])}}
                    </div>
                </div>
        
                <div class="form-group row">
                    {{Form::label('classification', 'Leave Class.', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::select('classification', ($loa_form->type == "sick") ? getLoaClassifications()["sick"] : getLoaClassifications()["regular"], $loa_form->classification, ['disabled', 'id' => 'sick_classification', 'placeholder' => 'Select Leave Classification', 'class' => 'form-control', 'onChange' => "loaClassification()"])}}
                    </div>
                </div>
                <div class="form-group row">
                    {{Form::label('reason', 'Reason/s', ['class' => 'col-sm-2 col-form-label'])}}
                    <div class="col-sm-10">
                        {{Form::textarea('reason', ($loa_form->reason) ? $loa_form->reason : 'N/A', ['readonly', 'id' => 'reason_input', 'class' => 'form-control', 'rows' => 3])}}
                    </div>
                </div>
            </div>

            <div class="col-3">
                <div class="card bg-light text-dark">
                    <div class="card-body">
                        {{Form::label('supervisor_remarks', 'Supervisor Remarks', ['class' => 'col-form-label'])}}
                        {{Form::textarea('supervisor_remarks', ($loa_form->supervisor_remarks) ? $loa_form->supervisor_remarks : 'N/A', ['readonly', 'class' => 'form-control', 'rows' => 5])}}
                        <hr>
                        {{Form::label('admin_remarks', 'Personnel Remarks', ['class' => 'col-form-label'])}}
                        {{Form::textarea('admin_remarks', ($loa_form->admin_remarks) ? $loa_form->admin_remarks : 'N/A', ['readonly', 'class' => 'form-control', 'rows' => 5])}}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        @if($loa_form->med_certificate)
            <div class="row">
                <div class="col-12">
                    <img class="img-thumbnail" src="/storage/med_certificates/{{$loa_form->med_certificate}}" alt="Medical Certificate">
                </div>
            </div>
        @endif

    </div>
@endsection