<div class="table-responsive">
    <table id="expired-alus-table" class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Time</th>
                <th>Date ALU Form Due</th>
                <th>Decision</th>
            </tr>
        </thead>
        <tbody>
            @if(count($alus) > 0)
                @foreach($alus as $alu)
                    <tr>
                        <td>{{$alu->date}}</td>
                        <td>{{$alu->type}}</td>
                        <td>{{($alu->time) ? $alu->time : '--:--:--'}}</td>
                        <td>{{$alu->date_alu_due}}</td>
                        <td>{{$alu->decision}}</td>                        
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No expired ALU Forms</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>