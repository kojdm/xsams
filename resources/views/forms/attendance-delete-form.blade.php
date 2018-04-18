{!! Form::open(['action' => 'TimekeepingController@destroyRange', 'method' => 'POST']) !!}
    {{Form::hidden('_method', 'DELETE')}}
    <div class="form-group row">
        {{Form::select('log_date_range', selectLogRanges(), null, ['placeholder' => 'Pick date range of logs to delete', 'class' => 'form-control'])}}
    </div>
    <div class="form-group row">
        {{Form::submit('Delete', ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure want to delete? All ALUs and ALU Forms will also be deleted.");'])}}
    </div>
{!! Form::close() !!}

{{--  {!! Form::open(['action' => 'TimekeepingController@destroyRange', 'method' => 'POST']) !!}
    {{Form::hidden('_method', 'DELETE')}}
    <div class="form-group row">
        <div class="col-sm-5">
            {{Form::select('start_date', $log_dates, null, ['placeholder' => 'Start Date', 'class' => 'form-control'])}}
        </div>
        <div class="col-sm-5">
            {{Form::select('end_date', $log_dates, null, ['placeholder' => 'End Date', 'class' => 'form-control'])}}
        </div>
        <div class="col-sm-2">
            {{Form::submit('Submit', ['class' => 'btn btn-success'])}}
        </div>
    </div>
{!! Form::close() !!}  --}}