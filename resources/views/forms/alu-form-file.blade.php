{!! Form::open(['action' => 'AluFormsController@store', 'method' => 'POST']) !!}
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
            {{Form::date('date_filed', getDateNow(), ['readonly', 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('type', 'Type', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            @if ($alu->type == 'A' || $alu->type == 'NT')
                {{Form::select('type', getAluTypes(), 'A', ['disabled', 'class' => 'form-control'])}}
            @elseif ($alu->type == 'L')
                {{Form::select('type', getAluTypes(), 'L', ['disabled', 'class' => 'form-control'])}}
            @elseif ($alu->type == 'U')
                {{Form::select('type', getAluTypes(), 'U', ['disabled', 'class' => 'form-control'])}}
            @endif
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('date', 'Date', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::date('date', $alu->date, ['readonly', 'class' => 'form-control'])}}
        </div>
        {{Form::label('time', 'Time', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::time('time', $alu->time, ['readonly', 'class' => 'form-control'])}}
        </div>
    </div>   
    <div class="form-group row">
        {{Form::label('reason', 'Reason/s', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::textarea('reason', '', ['class' => 'form-control', 'rows' => 3])}}
        </div>
    </div>

    {{Form::hidden('alu_id', $alu->id)}}

    <br>
    <div class="form-group row">
        {{Form::submit('Submit', ['class' => 'btn btn-success col-sm-12'])}}
    </div>
    <div class="form-group row">
        <a href="/home" class="btn btn-outline-secondary col-sm-12">Cancel</a>
    </div>
{!! Form::close() !!}