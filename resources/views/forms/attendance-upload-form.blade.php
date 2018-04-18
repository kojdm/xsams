{!! Form::open(['action' => 'TimekeepingController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group row">
        {{Form::file('attendance_log')}}
    </div>
    <div class="form-group row">
        {{Form::submit('Submit', ['class' => 'btn btn-success'])}}
    </div>
{!! Form::close() !!}