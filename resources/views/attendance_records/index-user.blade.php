@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>{{$attendance_record->user->last_name}}, {{$attendance_record->user->first_name}} {{$attendance_record->user->middle_name}} ({{$attendance_record->user->employee_num}})</h1>
            <h5>Attendance Record for <strong>{{$month}}</strong></h5>
        </div>
        <div class="col-3"><span class="float-right">
            @include('forms.months-select', ['action' => 'UserAttendanceRecordController@selectRange'])
        </span></div>
        <div class="col-3"><span class="float-right">
            @if($month != 'all months')
                <a href="/attendancerecord/{{date('Y-m', strtotime($month))}}/logs" class="btn btn-info btn-md">View Attendance Logs</a>
            @else
                <a href="/attendancerecord/all/logs" class="btn btn-info btn-md">View Attendance Logs</a>
            @endif
        </span></div>
    </div>
    <hr>
    
    <h3>Totals</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Days Worked</th>
                    <th>Absences</th>
                    <th>Lates</th>
                    <th>Undertimes</th>
                    <th>EO</th>
                    <th>EW</th>
                    <th>UO</th>
                    <th>UW</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{count($attendance_logs)}}</td>
                    <td>{{count($absences)}}</td>
                    <td>{{count($lates)}}</td>
                    <td>{{count($undertimes)}}</td>
                    <td>{{count($eo)}}</td>
                    <td>{{count($ew)}}</td>
                    <td>{{count($uo)}}</td>
                    <td>{{count($uw)}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    
    <h3>Absences</h3>
    @include('inc.alu-ar-table', ['alus' => $absences, 'type' => 'Absences'])

    <h3>Lates</h3>
    @include('inc.alu-ar-table', ['alus' => $lates, 'type' => 'Lates'])

    <h3>Undertimes</h3>
    @include('inc.alu-ar-table', ['alus' => $undertimes, 'type' => 'Undertimes'])
@endsection