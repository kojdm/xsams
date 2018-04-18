<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Date of ALU</th>
                <th>Employee No.</th>
                <th>Employee Name</th>
                <th>ALU Form Filed?</th>
                <th>Date Received</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Remark/Decision</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alus as $alu)
                <tr>
                    <td>{{date('j-M-y', strtotime($alu->date))}}</td>
                    <td>{{$alu->attendance_record->user->employee_num}}</td>
                    <td>{{$alu->attendance_record->user->last_name}}, {{$alu->attendance_record->user->first_name}}</td>
                    @if(! $alu->is_expired)
                        <td>{{($alu->is_alu_filed) ? 'Yes' : 'No'}}</td>
                    @else
                        <td>No (Expired)</td>
                    @endif
                    @if($alu->alu_form)
                        <td>{{date('j-M-y', strtotime($alu->alu_form->date_filed))}}</td>
                    @else
                        <td>-</td>
                    @endif
                    <td>{{$alu->type}}</td>
                    @if($alu->alu_form)
                        <td>{{$alu->alu_form->reason}}</td>
                    @else
                        <td>-</td>
                    @endif
                    <td>{{($alu->decision) ? $alu->decision : '-'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>