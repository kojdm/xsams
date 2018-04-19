<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Role;
use App\User;
use App\AttendanceRecord;
use App\LeaveCounter;
use DB;
use Carbon\Carbon;

// Give admin CRUD functionality for users

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('department_id', 'asc')->get();
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'departments' => Department::pluck('department_name', 'id'),
            'roles' => ['1' => 'Employee', '2' => 'Supervisor'],
        ];

        return view('users.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'employee_num' => 'required|unique:users',
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'role_id' => 'required',
            'department_id' => 'required',
            'position' => 'required',
            'shift_start' => 'required',
            'shift_end' => 'required|greater_than_field:shift_start'
        ]);

        $user = new User;
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->employee_num = $request->input('employee_num');
        $user->last_name = $request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->department_id = $request->input('department_id');
        $user->position = $request->input('position');
        $user->shift_start = $request->input('shift_start');
        $user->shift_end = $request->input('shift_end');
        $user->save();
        $user->roles()->attach(Role::find($request->input('role_id')));
    
        $ar = new AttendanceRecord;
        $ar->user_id = $user->id;
        $ar->save();

        $lc = new LeaveCounter;
        $lc->attendance_record_id = $ar->id;
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

        return redirect('/admin/users')->with('success', 'Employee Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
