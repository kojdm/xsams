<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>ALU Form Due Date</th>
                <th>ALU Form Filed?</th>
                <th>Decision</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($alus) > 0)
                @foreach($alus as $alu)
                    <tr>
                        <td>{{$alu->date}}</td>
                        <td>{{($alu->time) ? $alu->time : '--:--:--'}}</td>
                        <td>{{(!$alu->hasLoaForm()) ? $alu->date_alu_due : 'N/A (LOA Filed)'}}</td>
                        @if (! $alu->hasLoaForm())
                            @if(! $alu->is_expired)
                                <td>{{($alu->is_alu_filed) ? 'Yes' : 'No'}}</td>
                            @else
                                <td>No (Expired)</td>
                            @endif
                        @else
                            <td>N/A (LOA Filed)</td>
                        @endif
                        <td>{{($alu->decision) ? $alu->decision : 'N/A'}}</td>
                        @if($alu->is_alu_filed)
                            @auth('admin')
                                @if($alu->is_alu_approved)
                                    @if($alu->decision == null)
                                        <td><a href="/admin/aluform/{{base64_encode($alu->alu_form->id .' '. $alu->alu_form->date_filed)}}/decision" class="btn btn-outline-info btn-sm">View ALU</a></td>
                                    @else
                                        <td><a href="/admin/aluform/{{base64_encode($alu->alu_form->id .' '. $alu->alu_form->date_filed)}}" class="btn btn-outline-info btn-sm">View ALU</a></td>                                 
                                    @endif
                                @else
                                    <td><a href="#" class="btn btn-outline-secondary btn-sm disabled">Awaiting Approval</a></td>
                                @endif
                            @else
                                <td><a href="/aluform/{{base64_encode($alu->alu_form->id .' '. $alu->alu_form->date_filed)}}" class="btn btn-outline-info btn-sm">View ALU</a></td>
                            @endauth
                        @elseif($alu->hasLoaForm())
                            @auth('admin')
                                @if($alu->getLoaForm()->is_approved_supervisor)
                                    @if(! $alu->getLoaForm()->is_approved_admin)
                                        <td><a href="/admin/loaform/{{base64_encode($alu->getLoaForm()->id .' '. $alu->getLoaForm()->date_filed)}}/decision" class="btn btn-outline-info btn-sm">View LOA</a></td>
                                    @else
                                        <td><a href="/admin/loaform/{{base64_encode($alu->getLoaForm()->id .' '. $alu->getLoaForm()->date_filed)}}" class="btn btn-outline-info btn-sm">View LOA</a></td>                                 
                                    @endif
                                @else
                                    <td><a href="#" class="btn btn-outline-secondary btn-sm disabled">Awaiting Approval</a></td>
                                @endif
                            @else
                                <td><a href="/loaform/{{base64_encode($alu->getLoaForm()->id .' '. $alu->getLoaForm()->date_filed)}}" class="btn btn-outline-info btn-sm">View LOA</a></td>
                            @endauth
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No recorded {{$type}}</td>
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