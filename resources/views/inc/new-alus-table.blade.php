<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Emp. No.</th>
                <th>Name</th>
                <th>Date Filed</th>
                <th>Type</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($alu_forms) > 0)
                @foreach($alu_forms as $af)
                    <tr>
                        <td>{{$af->alu->attendance_record->user->employee_num}}</td>
                        <td>{{$af->alu->attendance_record->user->last_name}}, {{$af->alu->attendance_record->user->first_name}} {{$af->alu->attendance_record->user->middle_name}}</td>
                        <td>{{$af->date_filed}}</td>
                        <td>{{$af->alu->type}}</td>
                        @auth('admin')
                            <td><a href="/admin/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}/decision" class="btn btn-info btn-sm">View ALU</a></td>                            
                        @else
                            <td><a href="/aluform/{{base64_encode($af->id .' '. $af->date_filed)}}/decision" class="btn btn-info btn-sm">View ALU</a></td>                        
                        @endauth       
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No new ALU Forms</td>
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