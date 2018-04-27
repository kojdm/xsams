{!! Form::open(['action' => 'LoaFormsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
            {{Form::date('date_filed', getDateNow(), ['readonly', 'id' => 'date_filed', 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('type', 'Type of LOA', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::select('type', ['regular' => 'Regular LOA', 'sick' => 'Sick LOA'], null, ['placeholder' => 'Select LOA Type', 'id' => 'loa_type', 'onChange' => "loaClassification(); clearNumDays();", 'class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('inclusive_dates', 'Inclusive Dates', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::text('inclusive_dates', '', ['placeholder' => 'Select inclusive dates for leave', 'id' => 'inclusive_dates_sick', 'onChange' => "showDaysSick()", 'onkeypress' => "return false;", 'class' => 'form-control', 'autocomplete' => 'off'])}}
            {{Form::text('inclusive_dates', '', ['placeholder' => 'Select inclusive dates for leave', 'id' => 'inclusive_dates_regular', 'onChange' => "showDaysRegular()", 'onkeypress' => "return false;", 'class' => 'form-control', 'autocomplete' => 'off'])}}            
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('num_work_days', 'Number of Days', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::text('num_work_days', '', ['readonly', 'id' => 'num_days', 'class' => 'form-control'])}}
        </div>
    </div>

    <div class="form-group row">
        {{Form::label('classification', 'Leave Class.', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::select('classification', getLoaClassifications()["sick"], null, ['id' => 'sick_classification', 'placeholder' => 'Select Leave Classification', 'class' => 'form-control', 'onChange' => "loaClassification()"])}}
            {{Form::select('classification', getLoaClassifications()["regular"], null, ['id' => 'regular_classification', 'placeholder' => 'Select Leave Classification', 'class' => 'form-control', 'onChange' => "loaClassification()"])}}            
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('reason', 'Reason/s', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::textarea('reason', '', ['id' => 'reason_input', 'class' => 'form-control', 'rows' => 3])}}
        </div>
    </div>

    <div class="form-group row">
        {{Form::label('med_certificate', 'Medical Certificate', ['id' => 'med_certificate_label', 'class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::file('med_certificate', ['id' => 'med_certificate_input'])}}
        </div>
    </div>

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
        function showDaysSick() {
            var inclusive_dates = document.getElementById("inclusive_dates_sick").value;
            var num = inclusive_dates.split(", ");

            if (inclusive_dates != '') {
                document.getElementById("num_days").value = num.length;
            }
            else {
                document.getElementById("num_days").value = '';                
            }
        }

        function showDaysRegular() {
            var inclusive_dates = document.getElementById("inclusive_dates_regular").value;
            var num = inclusive_dates.split(", ");

            if (inclusive_dates != '') {
                document.getElementById("num_days").value = num.length;
            }
            else {
                document.getElementById("num_days").value = '';                
            }
        }

        function loaClassification() {
            var loa_type = document.getElementById("loa_type").value;

            var sick_classification = document.getElementById("sick_classification");
            var regular_classification = document.getElementById("regular_classification");

            var med_certificate_input = document.getElementById("med_certificate_input");
            var med_certificate_label = document.getElementById("med_certificate_label");

            if (loa_type == "sick") {
                sick_classification.style.display = 'block'; 
                sick_classification.disabled = false;                                             
                regular_classification.style.display = 'none';
                regular_classification.disabled = true;
                
                document.getElementById("reason_input").disabled = false;

                med_certificate_input.style.display = 'block';
                med_certificate_label.style.display = 'block';                
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
                    document.getElementById("reason_input").value = '';
                    document.getElementById("reason_input").disabled = true;                 
                }

                med_certificate_input.style.display = 'none';
                med_certificate_label.style.display = 'none';         
            }
            else {
                document.getElementById("regular_classification").style.display = 'none';
                document.getElementById("sick_classification").disabled = true;

                med_certificate_input.style.display = 'none';
                med_certificate_label.style.display = 'none';                
            }

            datepickerSettings();
        }

        function datepickerSettings() {
            if (document.getElementById("loa_type").value == "regular") {
                document.getElementById("inclusive_dates_regular").style.display = 'block';                         
                document.getElementById("inclusive_dates_regular").disabled = false;

                document.getElementById("inclusive_dates_sick").style.display = 'none';                         
                document.getElementById("inclusive_dates_sick").disabled = true;
                document.getElementById("inclusive_dates_sick").value = '';                
            }
            else if (document.getElementById("loa_type").value == "sick") {
                document.getElementById("inclusive_dates_sick").style.display = 'block';                         
                document.getElementById("inclusive_dates_sick").disabled = false;

                document.getElementById("inclusive_dates_regular").style.display = 'none';                         
                document.getElementById("inclusive_dates_regular").disabled = true;
                document.getElementById("inclusive_dates_regular").value = '';                
            }
            else {
                document.getElementById("inclusive_dates_regular").style.display = 'none';                
                document.getElementById("inclusive_dates_sick").disabled = true;
            }
        }

        function clearNumDays() {
            document.getElementById("num_days").value = '';
        }

        var date_now = new Date(document.getElementById("date_filed").value);
        $('#inclusive_dates_sick').datepicker({
            clearBtn: true,
            multidate: true,
            todayHighlight: true,
            endDate: date_now,
            multidateSeparator: ", "
        }); 
        var date_plus_three = new Date(date_now.setDate(date_now.getDate() + 3));
        $('#inclusive_dates_regular').datepicker({
            clearBtn: true,
            multidate: true,
            todayHighlight: true,
            startDate: date_plus_three,
            multidateSeparator: ", "
        }); 


        window.onload = function() {
            loaClassification();
            datepickerSettings();
        }
    </script>
@endpush