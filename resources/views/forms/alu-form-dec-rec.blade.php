{!! Form::open(['action' => [$action, $alu_form->id], 'method' => 'POST']) !!}
<div class="row">
    <div class="col-8">
        <div class="form-group row">
            {{Form::label('name', 'Name', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{Form::text('name', $user->last_name . ", " . $user->first_name . " " . $user->middle_name, ['readonly', 'class' => 'form-control'])}}
            </div>
        </div>
        <div class="form-group row">
            {{Form::label('employee_num', 'Emp. No.', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{Form::number('employee_num', $user->employee_num, ['readonly', 'class' => 'form-control'])}}
            </div>
        </div>
        <div class="form-group row">
            {{Form::label('date_filed', 'Date Filed', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{Form::date('date_filed', $alu_form->date_filed, ['readonly', 'class' => 'form-control'])}}
            </div>
        </div>
        <div class="form-group row">
            {{Form::label('type', 'Type', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{Form::select('type', getAluTypes(), $alu->type, ['disabled', 'class' => 'form-control'])}}
            </div>
        </div>
        <div class="form-group row">
            {{Form::label('date', 'Date', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-4">
                {{Form::date('date', $alu->date, ['readonly', 'class' => 'form-control'])}}
            </div>
            {{Form::label('time', 'Time', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-4">
                @if($alu->type == 'A' || $alu->type == 'NT')
                    {{Form::time('time', '', ['readonly', 'class' => 'form-control'])}}
                @else
                    {{Form::time('time', $alu->time, ['readonly', 'class' => 'form-control'])}}
                @endif
            </div>
        </div>   
        <div class="form-group row">
            {{Form::label('reason', 'Reason/s', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{Form::textarea('reason', $alu_form->reason, ['readonly', 'class' => 'form-control', 'rows' => 5])}}
            </div>
        </div>
    </div>

    <div class="col-4">
        @auth('admin')
            @if($alu->attendance_record->user->hasRole('supervisor'))
                {{Form::label('recommendation', 'Supervisor Recommendation', ['class' => 'col-form-label'])}}
                {{Form::select('recommendation', getDecisionChoices(), null, ['disabled', 'placeholder' => "N/A (Supervisor)", 'class' => 'form-control'])}}
                {{Form::label('supervisor_remarks', 'Supervisor Remarks', ['class' => 'col-form-label'])}}
                {{Form::textarea('supervisor_remarks', 'N/A (Supervisor)', ['readonly', 'class' => 'form-control', 'rows' => 2])}}
            @else
                {{Form::label('recommendation', 'Supervisor Recommendation', ['class' => 'col-form-label'])}}
                {{Form::select('recommendation', getDecisionChoices(), $alu_form->recommendation, ['disabled', 'placeholder' => "Select recommendation", 'class' => 'form-control'])}}
                {{Form::label('supervisor_remarks', 'Supervisor Remarks', ['class' => 'col-form-label'])}}
                {{Form::textarea('supervisor_remarks', ($alu_form->supervisor_remarks) ? $alu_form->supervisor_remarks : 'N/A', ['readonly', 'class' => 'form-control', 'rows' => 2])}}
            @endif
            <br>
        <div class="card bg-light text-dark">
            <div class="card-body">
                {{Form::label('decision', 'Decision', ['class' => 'col-form-label'])}}
                {{Form::select('decision', getDecisionChoices(), null, ['placeholder' => "Select decision", 'class' => 'form-control'])}}
                {{Form::label('admin_remarks', 'Remarks', ['class' => 'col-form-label'])}}
                {{Form::textarea('admin_remarks', '', ['class' => 'form-control', 'rows' => 2])}}
            </div>
        </div>
        @else
        <div class="card bg-light text-dark">
            <div class="card-body">
                {{Form::label('recommendation', 'Recommendation', ['class' => 'col-form-label'])}}
                {{Form::select('recommendation', getDecisionChoices(), null, ['placeholder' => "Select recommendation", 'class' => 'form-control'])}}
                {{Form::label('supervisor_remarks', 'Remarks', ['class' => 'col-form-label'])}}
                {{Form::textarea('supervisor_remarks', '', ['class' => 'form-control', 'rows' => 2])}}
            </div>
        </div>
        @endauth
    </div>
</div>

    {{Form::hidden('_method', 'PUT')}}

    <br>
    <div class="form-group row">
        {{Form::submit('Submit', ['class' => 'btn btn-success col-sm-12'])}}
    </div>
    <div class="form-group row">
        <a href="/admin" class="btn btn-outline-secondary col-sm-12">Cancel</a>
    </div>

{!! Form::close() !!}