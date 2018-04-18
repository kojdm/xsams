@extends('layouts.app')

@section('content')
    <h1>Pending LOA Forms</h1>
    <br>
    <div class="container">
        @include('inc.new-loas-table')
    </div>
@endsection