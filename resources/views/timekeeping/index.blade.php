@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>Timekeeping</h1>
        </div>
        <div class="col-6"><span class="float-right">
            <a href="/admin/timekeeping/delete" class="btn btn-outline-danger btn-lg">Delete Logs</a>
        </span></div>
    </div>
    <hr>
    
    <h3>Upload Attendance Log</h3>
    <div class="container">
        @include('forms.attendance-upload-form')
    </div>

    @if($range != '')
        <h3>Summary ({{$range}})</h3>
    @else
        <h3>Summary</h3>
    @endif
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