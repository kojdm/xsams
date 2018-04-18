@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">New ALU Forms</div>
                
                <div class="card-body">
                    @include('inc.new-alus-table')
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">New LOA Forms</div>
                
                <div class="card-body">
                    @include('inc.new-loas-table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection