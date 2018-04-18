@extends('layouts.app')

@section('content')
    <h1>Summary</h1>
    {{print_r($record)}}
    <br><br>
    {{print_r($logdisplay)}}

    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Emp. Num.</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            </thead>
            <tbody>
            @if(count($logdisplay) > 0)
                @foreach($logdisplay as $entry)
                    <tr>
                        <td>{{$entry[0]}}</td>
                        <td>{{$entry[1]}}</td>
                        <td>{{$entry[2]}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection