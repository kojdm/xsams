@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>Attendance Records</h1>
            <h4>for <strong>{{$month}}</strong></h4>
        </div>
        <div class="col-6"><span class="float-right">
            @include('forms.months-select', ['action' => 'AttendanceRecordsController@selectRange'])
        </span></div>
    </div>
    <hr>
    <div class="table-responsive">
        <table id="attendance-records-table" class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Department</th>
                <th>Emp. Num.</th>
                <th>Name</th>
                <th>Days Worked</th>
                <th>Absences</th>
                <th>Lates</th>
                <th>Undertimes</th>
                <th>EO</th>
                <th>EW</th>
                <th>UO</th>
                <th>UW</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @if(count($attendance_records) > 0)
                    @foreach($attendance_records as $ar)
                        <tr>
                            <td>{{$ar->user->department->department_name}}</td>
                            <td>{{$ar->user->employee_num}}</td>
                            <td>{{$ar->user->last_name}}, {{$ar->user->first_name}}</td>
                            <td>{{count($attendance_logs->where('attendance_record_id', $ar->id))}}</td>
                            <td>{{count($alus->where('attendance_record_id', $ar->id)->where('type', 'A'))}}</td>
                            <td>{{count($alus->where('attendance_record_id', $ar->id)->where('type', 'L'))}}</td>
                            <td>{{count($alus->where('attendance_record_id', $ar->id)->where('type', 'U'))}}</td>
                            <td>{{count($alus->where('attendance_record_id', $ar->id)->where('decision', 'EO'))}}</td>
                            <td>{{count($alus->where('attendance_record_id', $ar->id)->where('decision', 'EW'))}}</td>
                            <td>{{count($alus->where('attendance_record_id', $ar->id)->where('decision', 'UO'))}}</td>
                            <td>{{count($alus->where('attendance_record_id', $ar->id)->where('decision', 'UW'))}}</td>
                            @if ($month != 'all months')
                                <td><a href="/admin/attendancerecords/{{date('Y-m', strtotime($month))}}/{{$ar->user->employee_num}}" class="btn btn-outline-info btn-sm">View</a></td>
                            @else
                                <td><a href="/admin/attendancerecords/all/{{$ar->user->employee_num}}" class="btn btn-outline-info btn-sm">View</a></td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>Attendance Records empty</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" class="init">
        $(document).ready( function () {
            $('#attendance-records-table').DataTable();
        } );
    </script>
@endpush