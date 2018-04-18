<?php

// Compute for absences, lates, and undertimes for each employee in the database from a given attendance log file
if (! function_exists('compute')) 
{
    function compute($log, $employeesInDB)
    {
        // Retrieve data from the log file
        $logfile = file($log);
        array_shift($logfile);

        // Convert data from logfile to readable format
        $logarray = [];
        foreach ($logfile as $var) {
            $logarray[] = preg_replace('/\s+/', '~', trim(str_ireplace('"', '', $var)));
        }

        $empcode = array();
        $logdate = array();
        $logtime = array();
        foreach ($logarray as $line) {
            $data = explode("~", $line);
            $empcode[] = $data[0];
            $logdate[] = $data[1];
            $logtime[] = $data[2];
        }

        // Account for check-ins and check-outs during the day (for lunch and other reasons)
        $checkins = array();
        foreach ($empcode as $employee) {
            $checkins["$employee"] = [];
            foreach ($logdate as $date) {
                $checkins["$employee"]["$date"] = [];
            }
        }

        // Insert to Attendance Logs table timeins and timeouts for each employee for each date in the log file
        $record = array();
        for ($i = 0; $i <= count($empcode)-1; $i++) {
            $j = $i + 1;
            while ($j <= count($empcode)-1) {
                if ($empcode[$i] === $empcode[$j]) {
                    if ($logdate[$i] === $logdate[$j]) {
                        
                        array_push($checkins["$empcode[$i]"]["$logdate[$i]"], strtotime($logtime[$i]));
                        array_push($checkins["$empcode[$i]"]["$logdate[$i]"], strtotime($logtime[$j]));
                        
                        $timein = min($checkins["$empcode[$i]"]["$logdate[$i]"]); // Take earliest check-in time as time-in for the day
                        $timeout = max($checkins["$empcode[$i]"]["$logdate[$i]"]); // Take latest check-in time as time-out for the day

                        createAttendanceLog(date('Y-m-d', strtotime($logdate[$i])), $empcode[$i], date('H:i:s', $timein), date('H:i:s', $timeout), $log);

                        break;
                    }
                    else {
                        $j++;
                    }
                }
                else {
                    $j++;
                }
            }
        }

        // Collect each unique date from Attendance Logs as days
        $days = [];
        $attendance_logs = App\AttendanceLog::where('log_date_start', getLogRange($log)["start"])->where('log_date_end',  getLogRange($log)["end"])->get();
        foreach ($attendance_logs as $ld) {
            if (!in_array($ld->date, $days)) {
                array_push($days, $ld->date);
            }
        }

        // Update each employee's attendance record
        foreach ($employeesInDB as $employee) {
            
            $attendance_record = $employee->attendance_record;

            foreach ($days as $day) {

               $timein = DB::table('attendance_logs')->select('timein')->where('date', $day)->where('employee_num', $employee->employee_num)->value('timeout');
               $timeout = DB::table('attendance_logs')->select('timeout')->where('date', $day)->where('employee_num', $employee->employee_num)->value('timeout');

               $advanced_absence = searchAdvancedAlu('A', $attendance_record->id, $day);
               $advanced_late = searchAdvancedAlu('L', $attendance_record->id, $day);
               $advanced_undertime = searchAdvancedAlu('U', $attendance_record->id, $day);
               $loa_form_alu = searchLoaForm($attendance_record->id, $day);              

               // Check if employee is present for the day
                if (count(App\AttendanceLog::where('date', $day)->where('employee_num', $employee->employee_num)->get()) > 0) {
                    // Employee is NOT absent

                    // Check if an advanced ALU was filed for an absence that did not occur
                    if ($advanced_absence) {
                        // Delete advanced ALU and ALU Form
                        $advanced_absence->alu_form->delete();
                        $advanced_absence->delete();
                    }

                    // Check if employee is Late
                    if (strtotime($timein) > strtotime($employee->shift_start)) {
                        // Employee is late

                        // Check if advanced ALU Form was filed for this late
                        if ($advanced_late) {
                            // Advanced ALU Form filed

                            // Check if actual timein is later than timein in ALU Form
                            if (strtotime($timein) > strtotime($advanced_late->time)) {
                                // Actual timein is earlier than timein in ALU Form, new ALU is created
                                
                                $alu = new App\Alu;
                                $alu->attendance_record_id = $attendance_record->id;
                                $alu->type = 'L';
                                $alu->date = $day;
                                $alu->time = $timein;
                                $alu->date_alu_due = date('Y-m-d', strtotime($day. ' + 7 days'));
                                $alu->save();

                                // Delete old (advanced) ALU and ALU Form
                                $advanced_undertime->alu_form->delete();
                                $advanced_undertime->delete();
                            }

                            // Confirm that late actually occurred
                            $advanced_late->is_confirmed = true;
                            $advanced_late->save();
                        }
                        else {
                            $alu = new App\Alu;
                            $alu->attendance_record_id = $attendance_record->id;
                            $alu->type = 'L';
                            $alu->date = $day;
                            $alu->time = $timein;
                            $alu->date_alu_due = date('Y-m-d', strtotime($day. ' + 7 days'));                            
                            $alu->save();
                        }
                    }
                    else {
                        // Employee is NOT late

                        // Check if an advanced ALU was filed for a late that did not occur
                        if ($advanced_late) {
                            // Delete advanced ALU and ALU Form
                            $advanced_late->alu_form->delete();
                            $advanced_late->delete();
                        }
                    }

                    // Check if employee is Undertime
                    if (strtotime($timeout) < strtotime($employee->shift_end)) {
                        // Employee is undertime

                        // Check if advanced ALU Form was filed for this undertime
                        if ($advanced_undertime) {
                            // Advanced ALU Form filed

                            // Check if actual timeout is earlier than timeout in ALU Form
                            if (strtotime($timeout) < strtotime($advanced_undertime->time)) {
                                // Actual timeout is earlier than timeout in ALU Form, new ALU is created

                                $alu = new App\Alu;
                                $alu->attendance_record_id = $attendance_record->id;
                                $alu->type = 'U';
                                $alu->date = $day;
                                $alu->time = $timeout;
                                $alu->date_alu_due = date('Y-m-d', strtotime($day. ' + 7 days'));
                                $alu->save();

                                // Delete old (advanced) ALU and ALU Form
                                $advanced_undertime->alu_form->delete();
                                $advanced_undertime->delete();
                            }

                            // Confirm that undertime actually occurred
                            $advanced_undertime->is_confirmed = true;
                            $advanced_undertime->save();
                        }
                        else {
                            // No advanced ALU Form filed
                            $alu = new App\Alu;
                            $alu->attendance_record_id = $attendance_record->id;
                            $alu->type = 'U';
                            $alu->date = $day;
                            $alu->time = $timeout;
                            $alu->date_alu_due = date('Y-m-d', strtotime($day. ' + 7 days'));                            
                            $alu->save();
                        }
                    }
                    else {
                        // Employee is NOT undertime

                        // Check if an advanced ALU was filed for an undertime that did not occur
                        if ($advanced_undertime) {
                            // Delete advanced ALU and ALU Form
                            $advanced_undertime->alu_form->delete();
                            $advanced_undertime->delete();
                        }
                    }
                }
                else {
                    // Employee is Absent

                    // Check if advanced ALU Form was filed for this absence
                    if ($advanced_absence) {
                        // Advanced ALU Form filed

                        // Confirm that absence actually occurred
                        $advanced_absence->is_confirmed = true;
                        $advanced_absence->save();
                    }
                    elseif ($loa_form_alu) {
                        $loa_form_alu->is_confirmed = true;
                        $loa_form_alu->save();
                    }
                    else {
                        // No advanced ALU Form filed
                        $alu = new App\Alu;
                        $alu->attendance_record_id = $attendance_record->id;
                        $alu->type = 'A';
                        $alu->date = $day;
                        $alu->date_alu_due = date('Y-m-d', strtotime($day. ' + 7 days'));                       
                        $alu->save();
                    }
                }
            }
        }
    }
}

// Create Attendance Logs with time-in and time-out for each day for every employee in the log
if (! function_exists('createAttendanceLog'))
{
    function createAttendanceLog($date, $employee_num, $timein, $timeout, $log)
    {
        // Prevent duplication of attendance log entries
        if (App\AttendanceLog::where('date', $date)->where('employee_num', $employee_num)->get()->isEmpty()) {
            $al = new App\AttendanceLog;
            if (searchEmployee($employee_num)) {
                $al->attendance_record_id = searchEmployee($employee_num)->attendance_record->id;
            }
            else {
                $al->attendance_record_id = -1;
            }
            $al->log_date_start = getLogRange($log)["start"];
            $al->log_date_end = getLogRange($log)["end"];
            $al->date = $date;
            $al->employee_num = $employee_num;
            $al->timein = $timein;
            $al->timeout = $timeout;
            $al->save();
        }
    }
}

// Search for a user in the database given an employee number
if (! function_exists('searchEmployee'))
{
    function searchEmployee($employee_num)
    {
        return App\User::where('employee_num', $employee_num)->first();
    }
}

// Search if an advanced ALU Form was filed for a given ALU
if (! function_exists('searchAdvancedAlu'))
{
    function searchAdvancedAlu($type, $attendance_record_id, $date)
    {
        return App\Alu::where('attendance_record_id', $attendance_record_id)->where('type', $type)->where('date', $date)->where('is_alu_filed', 1)->where('is_confirmed', 0)->first();
    }
}

// Search if a LOA Form was filed for a given ALU
if (! function_exists('searchLoaForm'))
{
    function searchLoaForm($attendance_record_id, $date)
    {
        $alu = App\Alu::where('attendance_record_id', $attendance_record_id)->where('type', 'A')->where('date', $date)->where('is_alu_filed', 0)->where('is_confirmed', 0)->first();

        if ($alu) {
            if ($alu->hasLoaForm()) {
                return $alu;
            }
        }
    }
}

// Update decision and is_expired of all Users' ALUs
if (! function_exists('updateExpiredAlus'))
{
    function updateExpiredAlus() 
    {
        $users = App\User::all();

        foreach ($users as $user) {
            foreach ($user->attendance_record->alus->where('is_expired', 0) as $alu) {
                if (!$alu->hasLoaForm() && strtotime($alu->date_alu_due) < strtotime(getDateNow())) {
                    // ALU is already late
                    $expired_alu = App\Alu::find($alu->id);
                    $expired_alu->is_expired = true;
                    $expired_alu->decision = 'UW';
                    $expired_alu->save();
                }
            }
        }
    }
}

// Get the start date and end date of a given attendance log file
if (! function_exists('getLogRange')) 
{
    function getLogRange($log)
    {
        $logfile = file($log);
        array_shift($logfile);

        // Convert data from logfile to readable format
        $logarray = [];
        foreach ($logfile as $var) {
            $logarray[] = preg_replace('/\s+/', '~', trim(str_ireplace('"', '', $var)));
        }

        $dates = [];
        foreach ($logarray as $line) {
            $data = explode("~", $line);
            $dates[] = strtotime($data[1]);
        }

        $logrange = ["start" => date('Y-m-d', min($dates)), "end" => date('Y-m-d', max($dates))];
        return $logrange;
    }
}

// Provide all months of all logs for easy access in select form inputs
if (! function_exists('selectLogMonths')) 
{
    function selectLogMonths()
    {
        $months = ['all' => 'Show all months'];
        $attendance_logs = App\AttendanceLog::orderBy('date', 'desc')->get();
        $alus = App\Alu::orderBy('date', 'desc')->get();
        
        foreach ($attendance_logs as $log) {
            $month = date('F Y', strtotime($log->date));
            if (!in_array([strtotime($month) => $month], $months)) {
                $months += [strtotime($month) => $month];
            }
        }

        foreach ($alus as $alu) {
            $month = date('F Y', strtotime($alu->date));
            if (!in_array([strtotime($month) => $month], $months)) {
                $months += [strtotime($month) => $month];
            }
        }

        return $months;
    }
}

// Provide all log ranges of uploaded attendance logs for easy access in select form inputs
if (! function_exists('selectLogRanges')) 
{
    function selectLogRanges()
    {
        $log_ranges = [];
        $attendance_logs = App\AttendanceLog::orderBy('date', 'desc')->get();
        
        foreach ($attendance_logs as $log) {
            if (!in_array(["$log->log_date_start $log->log_date_end" => date('j M Y', strtotime($log->log_date_start)) ." – ". date('j M Y', strtotime($log->log_date_end))], $log_ranges)) {
                $log_ranges += ["$log->log_date_start $log->log_date_end" => date('j M Y', strtotime($log->log_date_start)) ." – ". date('j M Y', strtotime($log->log_date_end))];
            }
        }

        return $log_ranges;
    }
}

// Provide ALU Types for ALU Forms for easy access in select form inputs
if (! function_exists('getAluTypes'))
{
    function getAluTypes() 
    {
        return $choices = [
            '' => [
                'A' => 'Absent',
                'L' => 'Late',
                'U' => 'Undertime',
            ], 
            '──────────' => [
                'NT' => 'No Tap In/Out'
            ],
        ];
    }
}

// Provide decisions for ALUs for easy access in select form inputs
if (! function_exists('getDecisionChoices'))
{
    function getDecisionChoices() 
    {
        return $choices = [
            '' => [
                'EO' => 'EO – Excused w/o Deduction', 
                'EW' => 'EW – Excused w/ Deduction', 
                'UO' => 'UO – Unexcused w/o Deduction', 
                'UW' => 'UW – Unexcused w/ Deduction',
            ], 
            '──────────' => [
                'Edit' => 'Send back for edits'
            ],
        ];
    }
}

// Provide classifications for LOAs for easy access in select form inputs
if (! function_exists('getLoaClassifications'))
{
    function getLoaClassifications() 
    {
        return $classifications = [
            'regular' => [
                'VLP' => 'VLP – Vacation Leave with Pay', 
                'SRL' => 'SRL – Service Recognition Leave with Pay', 
                'WL' => 'WL – Wedding Leave', 
                'ML' => 'ML – Maternity Leave',
                'PL' => 'PL – Paternity Leave',
                'SPL' => 'SPL – Solo Parent Leave',
                'ELD' => 'ELD – Emergency Leave due to Death in the Family',
                'ELC' => 'ELC – Emergency Leave due to Calamity',
                'LWOP' => 'LWOP – Leave without Pay',
                'LWP' => 'LWP – Leave with Pay due to Official Business',
                'GL' => 'GL – Gynecological Leave',
                'VAWCL' => 'VAWCL – VAWC Leave',                               
            ], 
            'sick' => [
                'SLWP' => 'SLWP – Sick Leave with Pay',
                'SLWOP' => 'SLWOP – Sick Leave without Pay',
            ],
        ];
    }
}

// Return the current date in local timezone
if (! function_exists('getDateNow')) 
{
    function getDateNow()
    {
        return date('Y-m-d', strtotime(Carbon\Carbon::now('Asia/Manila')));
    }
}