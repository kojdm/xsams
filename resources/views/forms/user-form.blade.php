{!! Form::open(['action' => 'UsersController@store', 'method' => 'POST']) !!}
    <div class="form-group row">
        {{Form::label('employee_num', 'Employee Number', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::number('employee_num', '', ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('last_name', 'Last Name', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::text('last_name', '', ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('first_name', 'First Name', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::text('first_name', '', ['class' => 'form-control'])}}
        </div>
        {{Form::label('middle_name', 'Middle Name', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::text('middle_name', '', ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('role_id', 'Role', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-10">
            {{Form::select('role_id', $roles, 1, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
        {{Form::label('department_id', 'Department', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::select('department_id', $departments, null, ['placeholder' => 'Select Department', 'class' => 'form-control'])}}
        </div>
        {{Form::label('position', 'Position', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-4">
            {{Form::text('position', '', ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row">
            {{Form::label('shift_start', 'Shift Start', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-4">
                {{Form::time('shift_start', '', ['class' => 'form-control'])}}
            </div>
            {{Form::label('shift_end', 'Shift End', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-4">
                {{Form::time('shift_end', '', ['class' => 'form-control'])}}
            </div>
    </div>
    <div class="form-group row">
            <div class="col-sm-2"></div>
            {{Form::label('email', 'Email Address', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-6">
                {{Form::email('email', '', ['class' => 'form-control'])}}
            </div>
            <div class="col-sm-2"></div>
    </div>
    <div class="form-group row">
            <div class="col-sm-2"></div>
            {{Form::label('password', 'Password', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-6">
                {{Form::password('password', ['class' => 'form-control'])}}
            </div>
            <div class="col-sm-2"></div>
    </div>
    <div class="form-group row">
            <div class="col-sm-2"></div>
            {{Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-sm-2 col-form-label'])}}
            <div class="col-sm-6">
                {{Form::password('password_confirmation', ['class' => 'form-control'])}}
            </div>
            <div class="col-sm-2"></div>
    </div>
    <br>
    <div class="form-group row">
        {{Form::submit('Submit', ['class' => 'btn btn-success col-sm-12'])}}
    </div>
    <div class="form-group row">
        <a href="/admin/users" class="btn btn-outline-secondary col-sm-12">Cancel</a>
    </div>
{!! Form::close() !!}