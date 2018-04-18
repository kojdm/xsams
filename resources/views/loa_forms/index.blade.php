@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>LOA Forms</h1>
            <h4>filed on <strong>{{$month}}</strong></h4>
        </div>
        <div class="col-6"><span class="float-right">
            @auth('admin')
                @include('forms.months-select', ['action' => 'AdminLoaFormsController@selectRange'])                
            @else
                @include('forms.months-select', ['action' => 'LoaFormsController@selectRange'])
            @endauth
        </span></div>
    </div>
    <hr>
    <div class="table-responsive">
        <table id="loa-forms-table" class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Type</th>
                <th>Classification</th>                              
                <th>Date Filed</th>
                <th>Inclusive Dates</th>
                <th>Number of Days</th>                              
                <th>Approved?</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if(count($loa_forms) > 0)
                @foreach($loa_forms as $lf)
                    <tr>
                        <td>{{ucfirst($lf->type)}}</td>
                        <td>{{$lf->classification}}</td>
                        <td>{{$lf->date_filed}}</td>
                        <td>
                            @foreach ($lf->alus as $alu)
                                {{$alu->date}} <br>
                            @endforeach
                        </td>
                        <td>{{$lf->num_work_days}}</td>
                        <td>{{($lf->is_approved_admin) ? 'Yes' : 'Pending'}}</td>
                        @auth('admin')
                            <td><a href="/admin/loaform/{{base64_encode($lf->id .' '. $lf->date_filed)}}" class="btn btn-outline-info btn-sm">View LOA</a></td>
                        @else
                            <td><a href="/loaform/{{base64_encode($lf->id .' '. $lf->date_filed)}}" class="btn btn-outline-info btn-sm">View LOA</a></td>
                        @endauth
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
            $('#loa-forms-table').DataTable({
                "searching": false,
                "order": [],
            });
        } );
    </script>
@endpush