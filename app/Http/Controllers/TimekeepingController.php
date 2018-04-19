<?php

namespace App\Http\Controllers;

use App\Notifications\NewAlu;

use App\Jobs\SendNewAluEmail;
use Illuminate\Http\Request;
use App\User;
use App\AttendanceLog;
use App\AttendanceRecord;
use App\Alu;
use DB;
use File;

class TimekeepingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data = [
            'attendance_logs' => [],
            'range' => '',
        ];
        
        return view('timekeeping.index')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'attendance_log' => 'required|file|mimes:txt,csv'
         ]);
         
        // Get filename with extension
        $filenameWithExt = $request->file('attendance_log')->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('attendance_log')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        // Upload file
        $path = $request->file('attendance_log')->storeAs('attendance_logs', $fileNameToStore);

        $log = storage_path("app/attendance_logs/$fileNameToStore");

        // Prevent execution and update of logfile that has been uploaded before
        $logstart = getLogRange($log)["start"];
        $logend = getLogRange($log)["end"];
        if (! AttendanceLog::where('log_date_start', $logstart)->where('log_date_end', $logend)->exists()) {
            
            compute($log, User::orderBy('department_id', 'asc')->get());

            $attendance_logs = AttendanceLog::whereBetween('date', [$logstart, $logend])->get();

            $range = date('j M Y', strtotime(getLogRange($log)["start"])) . " – " . date('j M Y', strtotime(getLogRange($log)["end"]));
            $data = [
                'attendance_logs' => $attendance_logs,
                'range' => $range,
            ];

            // Send emails of new recorded ALUs to all employees in the database
            $this->sendEmails($logstart, $logend);

            File::cleanDirectory(storage_path("app/attendance_logs"));
            return view('timekeeping.index')->with($data);
        }
        else {
            File::cleanDirectory(storage_path("app/attendance_logs"));
            return redirect('/admin/timekeeping')->with('error', "Attendance log for $logstart – $logend already uploaded");
        }
    }

    private function sendEmails($start, $end)
    {
        $alus = Alu::whereBetween('date', [$start, $end])->get();

        foreach ($alus as $alu) {
            $user = $alu->attendance_record->user;
            $user->notify(new NewAlu($user, $alu));
        }
    }

    public function deleteIndex()
    {
        // Get first and last day of current month
        $start_date = date('Y-m-01', strtotime(getDateNow()));
        $end_date = date('Y-m-t', strtotime(getDateNow()));        

        // Retrieve Attendance Logs between start_date and end_date
        $attendance_logs = AttendanceLog::whereBetween('date', [$start_date, $end_date])->orderBy('date', 'asc')->get();

        $data = [
            'attendance_logs' => $attendance_logs,
            'start_date' => $start_date,
            'end_date' => $end_date, 
        ];

        return view('timekeeping.delete')->with($data);
    }

    public function selectRange(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Get start_date and end_date from form input
        $start_date = date('Y-m-d', strtotime($request->input('start_date')));
        $end_date = date('Y-m-d', strtotime($request->input('end_date')));

        // Retrieve Attendance Logs between start_date and end_date
        $attendance_logs = AttendanceLog::whereBetween('date', [$start_date, $end_date])->orderBy('date', 'asc')->get();

        $data = [
            'attendance_logs' => $attendance_logs,            
            'start_date' => $start_date,
            'end_date' => $end_date, 
        ];

        return view('timekeeping.delete')->with($data);        
    }

    public function destroy($params)
    {
        $start_date = explode(' ', base64_decode($params))[0];
        $end_date = explode(' ', base64_decode($params))[1];

        // Retrieve Attendance Logs and ALUs between start_date and end_date
        $attendance_logs = AttendanceLog::whereBetween('date', [$start_date, $end_date]);
        $alus = Alu::whereBetween('date', [$start_date, $end_date]);

        // Check if Attendance Logs exist in the database
        if (count($attendance_logs->get()) < 1) {
            return redirect('/admin/timekeeping/delete')->with('error', 'Attendance Logs are empty');
        }
        
        // Delete Attendance Logs and ALUs
        $attendance_logs->forceDelete();
        if ($alus) {
            $alus->forceDelete();
        }

        return redirect('/admin/timekeeping/delete')->with('success', "Attendance logs for $start_date – $end_date successfully deleted");
    }
}
