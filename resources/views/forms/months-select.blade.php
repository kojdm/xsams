{!! Form::open(['action' => $action, 'method' => 'POST']) !!}
    <div class="form-group row">
        <div class="col-sm-10">
            {{Form::select('month', selectLogMonths(), null, ['placeholder' => 'Month to display', 'class' => 'form-control'])}}
        </div>
        <div class="col-sm-2">
            {{Form::submit('Submit', ['class' => 'btn btn-success'])}}
        </div>
    </div>
{!! Form::close() !!}