@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>Employees</h1>
            @auth('web') <h4>{{Auth::user()->department->department_name}} Department</h4> @endauth
        </div>

        @auth('admin')
            <div class="col-6"><span class="float-right"><a href="/admin/users/create" class="btn btn-primary btn-lg">Add Employee</a></span></div>
        @endauth
    </div>
    <hr>
    <div class="table-responsive">
        <table id="users-table" class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Department</th>
                <th>Emp. Num.</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Position</th>
                <th>Shift Start</th>
                <th>Shift End</th>
                <th>Email</th>
            </tr>
            </thead>
            <tbody>
            @if(count($users) > 0)
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->department->department_name}}</td>
                        <td>{{$user->employee_num}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->middle_name}}</td>
                        <td>{{$user->position}}</td>
                        <td>{{$user->shift_start}}</td>
                        <td>{{$user->shift_end}}</td>
                        <td>{{$user->email}}</td>
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
            $('#users-table').DataTable();
        } );
    </script>
@endpush