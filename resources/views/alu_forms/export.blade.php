@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>ALU Summary</h1>
            <h4>for <strong>{{date('j M Y', strtotime($start_date))}} – {{date('j M Y', strtotime($end_date))}}</strong></h4>
            <a href="/admin/alus/export/{{base64_encode($start_date .' '. $end_date)}}" class="btn btn-primary btn-lg">Export</a>
        </div>
        <div class="col-6"><span class="float-right">
            @include('forms.start-end-date-select', ['action' => 'ExportController@selectRange'])
        </span></div>
    </div>
    <br>

    <div class="container">
        @include('inc.export-alus-table')
    </div>
@endsection