{!! Form::open(['action' => ['AluFormsController@update', $alu_form->id], 'method' => 'POST']) !!}
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
            {{Form::select('type', getAluTypes(), $alu->type, ['disabled', 'class' => 'form-control'])}}
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
            {{Form::textarea('reason', $alu_form->reason, ['class' => 'form-control', 'rows' => 3])}}
        </div>
    </div>
    @if(Auth::user()->hasRole('employee'))
        <div class="form-group row">
            {{Form::label('supervisor_remarks', 'Supervisor Remarks', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{Form::textarea('supervisor_remarks', ($alu_form->supervisor_remarks) ? $alu_form->supervisor_remarks : 'N/A', ['disabled', 'class' => 'form-control', 'rows' => 1])}}
            </div>
        </div>
    @endif
    <div class="form-group row">
        {{Form::label('admin_remarks', 'Personnel Remarks', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::textarea('admin_remarks', ($alu_form->admin_remarks) ? $alu_form->admin_remarks : 'N/A', ['disabled', 'class' => 'form-control', 'rows' => 1])}}
        </div>
    </div>

    {{Form::hidden('old_reason', $alu_form->reason)}}
    {{Form::hidden('_method', 'PUT')}}

    <br>
    <div class="form-group row">
        {{Form::submit('Submit', ['class' => 'btn btn-success col-sm-12'])}}
    </div>
    <div class="form-group row">
        <a href="{{URL::previous()}}" class="btn btn-outline-secondary col-sm-12">Cancel</a>
    </div>

{!! Form::close() !!}