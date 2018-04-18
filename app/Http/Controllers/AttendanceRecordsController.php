<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AttendanceRecord;
use App\AttendanceLog;
use App\Alu;

class AttendanceRecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        // Get current month
        $month_now = date('F Y', strtotime(getDateNow()));

        $attendance_records = AttendanceRecord::all();
        $alus = Alu::where('is_confirmed', 1)->whereYear('date', date('Y', strtotime($month_now)))->whereMonth('date', date('m', strtotime($month_now)))->get();
        $attendance_logs = AttendanceLog::whereYear('date', date('Y', strtotime($month_now)))->whereMonth('date', date('m', strtotime($month_now)))->get();

        $data = [
            'attendance_records' => $attendance_records,
            'alus' => $alus,
            'attendance_logs' => $attendance_logs,             
            'month' => $month_now,
        ];

        updateExpiredAlus();
        return view('attendance_records.index')->with($data);
    }

    public function updateExpiredAlus($uniqid)
    {
        updateExpiredAlus();
        return redirect('/admin/attendancerecords');
    }

    public function selectRange(Request $request)
    {
        $this->validate($request, [
            'month' => 'required',
        ]);

        if ($request->input('month') == 'all') {
            $month = 'all';
        }
        else {
            $month = date('Y-m', $request->input('month'));
        }

        return redirect("/admin/attendancerecords/$month");
    }

    public function indexWithRange($month) 
    {
        if ($month == 'all') {
            // Retrieve ALUs and Attendance Logs for all months
            $month = 'all months';
            $alus = Alu::where('is_confirmed', 1)->get();
            $attendance_logs = AttendanceLog::all();
        }
        else {
            // Retrieve ALUs and Attendance Logs for given month
            $month = date('F Y', strtotime($month));            
            $alus = Alu::where('is_confirmed', 1)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->get();
            $attendance_logs = AttendanceLog::whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->get();
        }

        $attendance_records = AttendanceRecord::all();
        
        $data = [
            'attendance_records' => $attendance_records,
            'alus' => $alus,
            'attendance_logs' => $attendance_logs,
            'month' => $month,
        ];

        return view('attendance_records.index')->with($data);
    }

    public function show($month, $employee_num) 
    {
        $user = searchEmployee($employee_num);

        // Check if user exists
        if ($user == null) {
            return abort(404);
        }

        $attendance_record = $user->attendance_record;

        if ($month == 'all') {
            // Retrieve ALUs and attendance logs for given user for all months
            $month = 'all months';
            $alus = $attendance_record->alus->where('is_confirmed', 1)->sortByDesc('date');
            $attendance_logs = $attendance_record->attendance_logs;
        }
        else {
            // Retrieve ALUs and attendance logs for given user for a given month
            $month = date('F Y', strtotime($month));
            $alus = Alu::where('attendance_record_id', $attendance_record->id)->where('is_confirmed', 1)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->orderBy('date', 'desc')->get();
            $attendance_logs = AttendanceLog::where('attendance_record_id', $attendance_record->id)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->get();
        }

        $absences = $alus->where('type', 'A');
        $lates = $alus->where('type', 'L');
        $undertimes = $alus->where('type', 'U');
        $eo = $alus->where('decision', 'EO');
        $ew = $alus->where('decision', 'EW');
        $uo = $alus->where('decision', 'UO');
        $uw = $alus->where('decision', 'UW');
        
        $data = [
            'attendance_record' => $attendance_record,
            'absences' => $absences,
            'lates' => $lates,
            'undertimes' => $undertimes,
            'eo' => $eo,
            'ew' => $ew,
            'uo' => $uo,
            'uw' => $uw,
            'attendance_logs' => $attendance_logs,
            'month' => $month,
        ];

        return view('attendance_records.show')->with($data);
    }

    public function showLogs($month, $employee_num) 
    {
        $user = searchEmployee($employee_num);

        // Check if user exists
        if ($user == null) {
            return abort(404);
        }

        $attendance_record = $user->attendance_record;

        if ($month == 'all') {
            $month = 'all months';
            $attendance_logs = $attendance_record->attendance_logs->sortByDesc('date');
        }
        else {
            // Retrieve attendance logs for given attendance record
            $month = date('F Y', strtotime($month));
            $attendance_logs = AttendanceLog::where('attendance_record_id', $attendance_record->id)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->orderBy('date', 'desc')->get();
        }

        $data = [
            'attendance_record' => $attendance_record,
            'attendance_logs' => $attendance_logs,
            'month' => $month,
        ];

        return view('attendance_records.show-logs')->with($data);
    }
}
