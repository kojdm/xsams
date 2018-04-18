@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>Completed ALU Forms</h1>
            <h4>for <strong>{{$month}}</strong></h4>
        </div>
        <div class="col-6"><span class="float-right">
            @include('forms.months-select', ['action' => 'AdminAluFormsController@selectRange'])
        </span></div>
    </div>
    <hr>

    <div class="table-responsive">
        <table id="alu-forms-table" class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Emp. No.</th>
                <th>Name</th>
                <th>Date</th>
                <th>Type</th>
                <th>Time</th>
                <th>Date Filed</th>
                <th>Date Due</th>
                <th>Decision</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if(count($alu_forms) > 0)
                @foreach($alu_forms as $af)
                    <tr>
                        <td>{{$af->alu->attendance_record->user->employee_num}}</td>
                        <td>{{$af->alu->attendance_record->user->last_name}}, {{$af->alu->attendance_record->user->first_name}}</td>
                        <td>{{$af->alu->date}}</td>
                        <td>{{$af->alu->type}}</td>
                        @if($af->alu->type == 'A' || $af->alu->type == 'NT')
                            <td>--:--:--</td>                            
                        @else
                            <td>{{$af->alu->time}}</td>
                        @endif
                        <td>{{$af->date_filed}}</td>
                        <td>{{$af->alu->date_alu_due}}</td>
                        <td>{{$af->alu->decision}}</td>
                        <td><a href="/admin/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}" class="btn btn-outline-info btn-sm">View ALU</a></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No completed ALU Forms</td>
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
            $('#alu-forms-table').DataTable();
        } );
    </script>
@endpush