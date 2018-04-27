{!! Form::open(['action' => 'AluFormsController@storeAdvanced', 'method' => 'POST']) !!}
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
            {{Form::select('type', getAluTypes(), null, ['placeholder' => 'Select ALU Type', 'id' => 'type_input', 'onChange' => "disableTime()", 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('date', 'Date', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::date('date', '', ['class' => 'form-control'])}}
        </div>
        {{Form::label('time', 'Time', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::time('time', '', ['id' => 'time_input', 'class' => 'form-control'])}}
        </div>
    </div>   
    <div class="form-group row">
        {{Form::label('reason', 'Reason/s', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::textarea('reason', '', ['class' => 'form-control', 'rows' => 3])}}
        </div>
    </div>

    {{Form::hidden('shift_start', $user->shift_start)}}
    {{Form::hidden('shift_end', $user->shift_end)}}

    <br>
    <div class="form-group row">
        {{Form::submit('Submit', ['class' => 'btn btn-success col-sm-12', 'onclick' => 'return confirm("Are you sure want to submit this ALU Form?");'])}}
    </div>
    <div class="form-group row">
        <a href="/" class="btn btn-outline-secondary col-sm-12">Cancel</a>
    </div>
{!! Form::close() !!}

@push('scripts')
    <script type="text/javascript" class="init">
       function disableTime() {
            if (document.getElementById("type_input").value == "A" || document.getElementById("type_input").value == "NT") {
                document.getElementById("time_input").value = "";
                document.getElementById("time_input").disabled = true;
           }
           else {
                document.getElementById("time_input").disabled = false;
           }
       }
       window.onload = function() {
           disableTime();
       }
    </script>
@endpush