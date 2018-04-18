@extends('layouts.app')

@section('content')
<div class="container">

@if(Auth::user()->hasRole('employee')) 
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">Pending ALU Forms for <strong>{{$user->first_name}} {{$user->last_name}}</strong></div>

                <div class="card-body">

                    <h4>Absences</h4>
                    @include('inc.alu-home-table', $alus = $absences)

                    <h4>Lates</h4>
                    @include('inc.alu-home-table', $alus = $lates)

                    <h4>Undertimes</h4>
                    @include('inc.alu-home-table', $alus = $undertimes)

                    <hr>

                    <h4>Sent back for Edits</h4>
                    @include('inc.alu-home-table', ['alus' => $edits, 'type' => 'edits'])

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-dark text-white">Leave Summary</div>
                <div class="card-body">

                    @include('inc.leave-summary-table', $lc = $user->attendance_record->currentLeaveCounter())

                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">Leave Summary</div>
                <div class="card-body">
                    
                    @include('inc.leave-summary-table', $lc = $user->attendance_record->currentLeaveCounter())

                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">Pending ALU Forms for <strong>{{$user->first_name}} {{$user->last_name}}</strong></div>

                <div class="card-body">

                    <h4>Absences</h4>
                    @include('inc.alu-home-table', $alus = $absences)

                    <h4>Lates</h4>
                    @include('inc.alu-home-table', $alus = $lates)

                    <h4>Undertimes</h4>
                    @include('inc.alu-home-table', $alus = $undertimes)

                    <hr>

                    <h4>Sent back for Edits</h4>
                    @include('inc.alu-home-table', ['alus' => $edits, 'type' => 'edits'])

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">Forms for Approval ({{$user->department->department_name}} Department)</div>

                <div class="card-body">

                    <h4>ALU Forms</h4>
                    @include('inc.new-alus-table', $alu_forms = $alus_for_approval)

                    <h4>LOA Forms</h4>
                    @include('inc.new-loas-table', $loa_forms = $loas_for_approval)

                </div>
            </div>
        </div>
    </div>
@endif

</div>
@endsection
