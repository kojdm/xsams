{!! Form::open(['action' => $action, 'method' => 'POST']) !!}
    <div class="form-group row">
        <div class="col-sm-5">
            {{Form::date('start_date', $start_date, ['class' => 'form-control'])}}
        </div>
        <div class="col-sm-5">
            {{Form::date('end_date', $end_date, ['class' => 'form-control'])}}
        </div>
        <div class="col-sm-2">
            {{Form::submit('Submit', ['class' => 'btn btn-success'])}}
        </div>
    </div>
{!! Form::close() !!}