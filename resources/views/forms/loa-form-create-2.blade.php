{!! Form::open(['action' => 'AluFormsController@storeAdvanced', 'method' => 'POST']) !!}
    <div class="form-group row">
        {{Form::label('name', 'Name', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::text('name', $user->last_name . ", " . $user->first_name . " " . $user->middle_name, ['readonly', 'class' => 'form-control'])}}
        </div>
        {{Form::label('employee_num', 'Emp. No.', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::number('employee_num', $user->employee_num, ['readonly', 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('date_filed', 'Date Filed', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::date('date_filed', getDateNow(), ['readonly', 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('type', 'Type of LOA', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::select('type', ['regular' => 'Regular LOA', 'sick' => 'Sick LOA'], null, ['placeholder' => 'Select LOA Type', 'id' => 'loa_type', 'onChange' => "loaClassification()", 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('start_date', 'Start Date', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::date('start_date', '', ['id' => 'start_date', 'onChange' => "showDays()", 'class' => 'form-control'])}}
        </div>
        {{Form::label('end_date', 'End Date', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::date('end_date', '', ['id' => 'end_date', 'onChange' => "showDays()", 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('num_days', 'Number of Days', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::text('num_days', '', ['readonly', 'id' => 'num_days', 'class' => 'form-control'])}}
        </div>
        {{Form::label('num_work_days', 'Number of Work Days', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::text('num_work_days', '', ['id' => 'num_work_days', 'class' => 'form-control'])}}
        </div>
    </div>

    <div class="form-group row">
        {{Form::label('classification', 'Leave Class.', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::select('classification', getLoaClassifications()["sick"], null, ['id' => 'sick_classification', 'placeholder' => 'Select Leave Classification', 'class' => 'form-control'])}}
            {{Form::select('classification', getLoaClassifications()["regular"], null, ['id' => 'regular_classification', 'placeholder' => 'Select Leave Classification', 'class' => 'form-control', 'onChange' => "loaClassification()"])}}            
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('reason', 'Reason/s', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::textarea('reason', '', ['id' => 'reason_input', 'class' => 'form-control', 'rows' => 3])}}
        </div>
    </div>

    {{Form::hidden('shift_start', $user->shift_start)}}
    {{Form::hidden('shift_end', $user->shift_end)}}

    <br>
    <div class="form-group row">
        {{Form::submit('Submit', ['class' => 'btn btn-success col-sm-12'])}}
    </div>
    <div class="form-group row">
        <a href="/" class="btn btn-outline-secondary col-sm-12">Cancel</a>
    </div>
{!! Form::close() !!}

@push('scripts')
    <script type="text/javascript" class="init">
        function showDays() {
            var start_date = new Date(document.getElementById("start_date").value);
            var end_date = new Date(document.getElementById("end_date").value);

            if (start_date != 'Invalid Date' && end_date != 'Invalid Date') {
                document.getElementById("num_days").value = Math.round((end_date - start_date)/(1000*60*60*24));
            }
            else {
                document.getElementById("num_days").value = '';
            }
        }

        function loaClassification() {
            var loa_type = document.getElementById("loa_type").value;

            var sick_classification = document.getElementById("sick_classification");
            var regular_classification = document.getElementById("regular_classification");            

            if (loa_type == "sick") {
                sick_classification.style.display = 'block'; 
                sick_classification.disabled = false;                                             
                regular_classification.style.display = 'none';
                regular_classification.disabled = true;
                
                document.getElementById("reason_input").disabled = false;
            }
            else if (loa_type == "regular") {
                regular_classification.style.display = 'block'; 
                regular_classification.disabled = false;                                             
                sick_classification.style.display = 'none';
                sick_classification.disabled = true;

                if (regular_classification.value == "LWP" || regular_classification.value == "LWOP") {
                    document.getElementById("reason_input").disabled = false;                    
                }
                else {
                    document.getElementById("reason_input").disabled = true;                    
                }
            }
        }

        window.onload = function() {
            document.getElementById("regular_classification").style.display = 'none';
            document.getElementById("sick_classification").disabled = true;      
        }

        $('#inclusive_dates').datepicker({
            clearBtn: true,
            multidate: true,
            todayHighlight: true,
            multidateSeparator: ", "
        });
    </script>
@endpush