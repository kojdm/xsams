<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Date</th>
                @if(isset($type)) <th>Type</th> @endif
                <th>Time</th>
                <th>Date Due</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($alus) > 0)
                @foreach($alus as $alu)
                    @if($alu->decision == 'Edit')
                        <tr class="table-danger">
                    @else
                        <tr>
                    @endif
                        <td>{{$alu->date}}</td>
                        @if(isset($type)) <td>{{$alu->type}}</td> @endif
                        @if($alu->time)
                            <td>{{$alu->time}}</td>
                        @else
                            <td>--:--:--</td>
                        @endif
                        <td>{{date('D, j M Y', strtotime($alu->date . ' + 7 days'))}}</td>
                        @if($alu->decision == 'Edit')
                            <td><a href="/aluform/{{base64_encode($alu->alu_form->id .' '. $alu->alu_form->date_filed)}}/edit" class="btn btn-info btn-sm">Edit ALU</a></td>
                        @else
                            <td><a href="/aluforms/file/{{base64_encode($alu->id .' '. $alu->date)}}" class="btn btn-info btn-sm">File ALU</a></td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No pending ALU Forms</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if(isset($type)) <td></td> @endif
                </tr>
            @endif
        </tbody>
    </table>
</div>