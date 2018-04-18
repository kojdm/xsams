@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>ALU Forms</h1>
            <h4>for <strong>{{$month}}</strong></h4>
        </div>
        <div class="col-6"><span class="float-right">
            @include('forms.months-select', ['action' => 'AluFormsController@selectRange'])
        </span></div>
    </div>
    <hr>
    <div class="table-responsive">
        <table id="alu-forms-table" class="table">
            <thead>
            <tr>
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
                    @if($af->alu->decision == 'Edit')
                        <tr class="table-danger">
                    @else
                        <tr>
                    @endif
                        <td>{{$af->alu->date}}</td>
                        <td>{{$af->alu->type}}</td>                                                
                        @if($af->alu->type == 'A' || $af->alu->type == 'NT')
                            <td>--:--:--</td>                            
                        @else
                            <td>{{$af->alu->time}}</td>
                        @endif
                        <td>{{$af->date_filed}}</td>
                        <td>{{$af->alu->date_alu_due}}</td>
                        @if($af->alu->decision)
                            <td>{{$af->alu->decision}}</td>
                        @else
                            <td>N/A</td>
                        @endif
                        @if($af->alu->decision == 'Edit')
                            <td><a href="/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}/edit" class="btn btn-outline-info btn-sm">Edit ALU</a></td>
                        @else
                            <td><a href="/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}" class="btn btn-outline-info btn-sm">View ALU</a></td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No ALU Forms filed.</td>
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
            $('#alu-forms-table').DataTable({
                "searching": false,
                "order": [],
            });
        } );
    </script>
@endpush