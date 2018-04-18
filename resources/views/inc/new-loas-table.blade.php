<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Emp. No.</th>
                <th>Name</th>
                <th>Date Filed</th>
                <th>Class.</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($loa_forms) > 0)
                @foreach($loa_forms as $lf)
                    <tr>
                        <td>{{$lf->attendance_record->user->employee_num}}</td>
                        <td>{{$lf->attendance_record->user->last_name}}, {{$lf->attendance_record->user->first_name}} {{$lf->attendance_record->user->middle_name}}</td>
                        <td>{{$lf->date_filed}}</td>
                        <td>{{$lf->classification}}</td>
                        @auth('admin')
                            <td><a href="/admin/loaform/{{base64_encode($lf->id .' '. $lf->date_filed)}}/decision" class="btn btn-info btn-sm">View LOA</a></td>                            
                        @else
                            <td><a href="/loaform/{{base64_encode($lf->id .' '. $lf->date_filed)}}/decision" class="btn btn-info btn-sm">View LOA</a></td>
                        @endauth                         
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No new LOA Forms</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>