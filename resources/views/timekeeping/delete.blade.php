@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>Timekeeping</h1>
        </div>
        <div class="col-6"><span class="float-right">
            <a href="/admin/timekeeping/" class="btn btn-outline-info btn-lg">Upload Logs</a>
        </span></div>
    </div>
    <hr>
    <div class="row">
        <div class="col-6">
            <h3>Delete Attendance Logs</h3>
            <h5>for <strong>{{date('j M Y', strtotime($start_date))}} – {{date('j M Y', strtotime($end_date))}}</strong></h5>
            <a href="/admin/timekeeping/delete/{{base64_encode($start_date .' '. $end_date)}}" class="btn btn-danger btn-lg" onclick="return confirm('Are you sure want to delete? All ALUs and ALU Forms will also be deleted.');">Delete</a>
        </div>
        <div class="col-6"><span class="float-right">
            @include('forms.start-end-date-select', ['action' => 'TimekeepingController@selectRange'])
        </span></div>
    </div>
    <br>

    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
            <tr>
                <th>Emp. Num.</th>
                <th>Date</th>
                <th>Time-in</th>
                <th>Time-out</th>
            </tr>
            </thead>
            <tbody>
                @if(count($attendance_logs) > 0)
                    @foreach($attendance_logs as $log)
                        <tr>
                            <td>{{$log->employee_num}}</td>
                            <td>{{$log->date}}</td>
                            <td>{{$log->timein}}</td>
                            <td>{{$log->timeout}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection