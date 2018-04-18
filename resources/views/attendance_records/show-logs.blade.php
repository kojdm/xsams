@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>{{$attendance_record->user->last_name}}, {{$attendance_record->user->first_name}} {{$attendance_record->user->middle_name}} ({{$attendance_record->user->employee_num}})</h1>
            <h5>Attendance Logs for <strong>{{$month}}</strong></h5>
        </div>
        <div class="col-6"><span class="float-right">
            @if($month != 'all months')
                <a href="/admin/attendancerecords/{{date('Y-m', strtotime($month))}}/{{$attendance_record->user->employee_num}}" class="btn btn-info btn-md">View Attendance Record</a>
            @else
                <a href="/admin/attendancerecords/all/{{$attendance_record->user->employee_num}}" class="btn btn-info btn-md">View Attendance Record</a>
            @endif
        </span></div>
    </div>
    <hr>

    <div class="table-responsive">
        <table id="show-logs-table" class="table table-condensed">
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

@push('scripts')
    <script type="text/javascript" class="init">
        $(document).ready( function () {
            $('#show-logs-table').DataTable({
                "searching": false,
                "order": [],
            });
        } );
    </script>
@endpush