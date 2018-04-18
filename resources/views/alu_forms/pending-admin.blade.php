@extends('layouts.app')

@section('content')
    <h1>Pending ALU Forms</h1>
    <br>

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Emp. No.</th>
                <th>Name</th>
                <th>Date Filed</th>
                <th>Date Due</th>
                <th>Type</th>
                <th>Date</th>
                <th>Time</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if(count($alu_forms_pending) > 0)
                @foreach($alu_forms_pending as $af)
                    <tr>
                        <td>{{$af->alu->attendance_record->user->employee_num}}</td>
                        <td>{{$af->alu->attendance_record->user->last_name}}, {{$af->alu->attendance_record->user->first_name}}</td>
                        <td>{{$af->date_filed}}</td>
                        <td>{{$af->alu->date_alu_due}}</td>
                        <td>{{$af->alu->type}}</td>
                        <td>{{$af->alu->date}}</td>
                        @if($af->alu->type == 'A' || $af->alu->type == 'NT')
                            <td>--:--:--</td>                            
                        @else
                            <td>{{$af->alu->time}}</td>
                        @endif
                        @if($af->alu->decision != 'Edit')
                            <td><a href="/admin/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}/decision" class="btn btn-outline-info btn-sm">View ALU</a></td>
                        @else
                            <td><a href="/admin/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}" class="btn btn-outline-info btn-sm">View ALU</a></td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No pending ALU Forms</td>
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

    <hr>

    <h3>Sent back for Edits</h3>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Emp. No.</th>
                <th>Name</th>
                <th>Date Filed</th>
                <th>Date Due</th>
                <th>Type</th>
                <th>Date</th>
                <th>Time</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if(count($alu_forms_edit) > 0)
                @foreach($alu_forms_edit as $af)
                    <tr>
                        <td>{{$af->alu->attendance_record->user->employee_num}}</td>
                        <td>{{$af->alu->attendance_record->user->last_name}}, {{$af->alu->attendance_record->user->first_name}}</td>
                        <td>{{$af->date_filed}}</td>
                        <td>{{$af->alu->date_alu_due}}</td>
                        <td>{{$af->alu->type}}</td>
                        <td>{{$af->alu->date}}</td>
                        @if($af->alu->type == 'A' || $af->alu->type == 'NT')
                            <td>--:--:--</td>                            
                        @else
                            <td>{{$af->alu->time}}</td>
                        @endif
                        <td><a href="/admin/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}" class="btn btn-outline-info btn-sm">View ALU</a></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No pending ALU Forms</td>
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