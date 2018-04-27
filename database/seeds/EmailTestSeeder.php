<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\LeaveCounter;
use Carbon\Carbon;

class EmailTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'email' => 'admin@xs.edu.ph',
            'password' => bcrypt('password'),
        ]);

        DB::table('departments')->insert([
            'department_name' => 'Accounting',
        ]);

        DB::table('departments')->insert([
            'department_name' => 'Athletics',
        ]);

        DB::table('departments')->insert([
            'department_name' => 'NEXT',
        ]);

        // Create User/Employee Roles
        $role_employee = new Role;
        $role_employee->name = 'employee';
        $role_employee->save();

        $role_supervisor = new Role;
        $role_supervisor->name = 'supervisor';
        $role_supervisor->save();

        // Create Users - Employees
        $user = new User;
        $user->email = 'norenceilicito@gmail.com';
        $user->password = bcrypt('password');
        $user->employee_num = 666420;
        $user->last_name = 'Ilicito';
        $user->first_name = 'Norence';
        $user->middle_name = 'G.';
        $user->department_id = 1;
        $user->position = 'Supervisor';
        $user->shift_start = '09:00:00';
        $user->shift_end = '17:00:00';
        $user->save();
        $user->roles()->attach($role_supervisor);

        // Create Attendance Records
        DB::table('attendance_records')->insert([
            'user_id' => 1,
        ]);

        $lc = new LeaveCounter;
        $lc->attendance_record_id = 1;
        $date_now = Carbon::now('Asia/Manila');
        if ($date_now->month >= 6) {
            $ys = $date_now->year;
            $ye = $date_now->addYear()->year;
        }
        else {
            $ye = $date_now->year;
            $ys = $date_now->subYear()->year;
        }
        $lc->year_start = $ys;
        $lc->year_end = $ye;
        $lc->save();
    }
}
