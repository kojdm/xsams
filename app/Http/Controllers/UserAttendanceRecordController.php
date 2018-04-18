<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Alu;
use App\AttendanceLog;

class UserAttendanceRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $attendance_record = $user->attendance_record;
        $month = date('F Y', strtotime(getDateNow()));

        if ($month == 'all') {
            // Retrieve ALUs and attendance logs for given user for all months
            $month = 'all months';
            $alus = $attendance_record->alus->sortByDesc('date');
            $attendance_logs = $attendance_record->attendance_logs;
        }
        else {
            // Retrieve ALUs and attendance logs for given user for a given month
            $month = date('F Y', strtotime($month));
            $alus = Alu::where('is_confirmed', 1)->where('attendance_record_id', $attendance_record->id)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->orderBy('date', 'desc')->get();
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

        return view('attendance_records.index-user')->with($data);
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

        return redirect("/attendancerecord/$month");
    }

    public function indexWithRange($month)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $attendance_record = $user->attendance_record;

        if ($month == 'all') {
            // Retrieve ALUs and attendance logs for given user for all months
            $month = 'all months';
            $alus = $attendance_record->alus->sortByDesc('date');
            $attendance_logs = $attendance_record->attendance_logs;
        }
        else {
            // Retrieve ALUs and attendance logs for given user for a given month
            $month = date('F Y', strtotime($month));
            $alus = Alu::where('is_confirmed', 1)->where('attendance_record_id', $attendance_record->id)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->orderBy('date', 'desc')->get();
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

        return view('attendance_records.index-user')->with($data);
    }

    public function showLogs($month)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
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

        return view('attendance_records.show-logs-user')->with($data);        
    }
}
