@extends('layouts.app')

@section('content')
    <h1>Expired ALU Forms</h1>
    <br>

    @include('inc.expired-alus-table')
@endsection

@push('scripts')
    <script type="text/javascript" class="init">
        $(document).ready( function () {
            $('#expired-alus-table').DataTable({
                "searching": false,
                "order": [],
            });
        } );
    </script>
@endpush