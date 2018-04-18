<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\User;
use App\LeaveCounter;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
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
        $user->email = 'rmcdonald@xs.edu.ph';
        $user->password = bcrypt('password');
        $user->employee_num = 141262;
        $user->last_name = 'McDonald';
        $user->first_name = 'Ronald';
        $user->middle_name = 'O.';
        $user->department_id = 1;
        $user->position = 'Clown';
        $user->shift_start = '07:00:00';
        $user->shift_end = '16:00:00';
        $user->save();
        $user->roles()->attach($role_employee);

        $user = new User;
        $user->email = 'minasal@xs.edu.ph';
        $user->password = bcrypt('password');
        $user->employee_num = 143552;
        $user->last_name = 'Inasal';
        $user->first_name = 'Mang';
        $user->middle_name = 'K.';
        $user->department_id = 2;
        $user->position = 'Manong';
        $user->shift_start = '08:00:00';
        $user->shift_end = '17:00:00';
        $user->save();
        $user->roles()->attach($role_employee);

        $user = new User;
        $user->email = 'jbee@xs.edu.ph';
        $user->password = bcrypt('password');
        $user->employee_num = 187000;
        $user->last_name = 'Bee';
        $user->first_name = 'Jolly';
        $user->middle_name = 'B.';
        $user->department_id = 3;
        $user->position = 'Bee';
        $user->shift_start = '09:00:00';
        $user->shift_end = '18:00:00';
        $user->save();
        $user->roles()->attach($role_employee);

        // Create Users - Supervisors
        $user = new User;
        $user->email = 'cking@xs.edu.ph';
        $user->password = bcrypt('password');
        $user->employee_num = 198888;
        $user->last_name = 'King';
        $user->first_name = 'Chow';
        $user->middle_name = 'E.';
        $user->department_id = 1;
        $user->position = 'Direct Supervisor';
        $user->shift_start = '09:00:00';
        $user->shift_end = '18:00:00';
        $user->save();
        $user->roles()->attach($role_supervisor);

        // Create Attendance Records
        DB::table('attendance_records')->insert([
            'user_id' => 1,
        ]);

        DB::table('attendance_records')->insert([
            'user_id' => 2,
        ]);

        DB::table('attendance_records')->insert([
            'user_id' => 3,
        ]);

        DB::table('attendance_records')->insert([
            'user_id' => 4,
        ]);

        // Create Leave Counters
        for ($i = 1; $i <= 4; ++$i) {
            $lc = new LeaveCounter;
            $lc->attendance_record_id = $i;
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
}
