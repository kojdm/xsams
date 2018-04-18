<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function attendance_logs() {
        return $this->hasMany('App\AttendanceLog');
    }

    public function alus() {
        return $this->hasMany('App\Alu');
    }

    public function loa_forms() {
        return $this->hasMany('App\LoaForm');
    }

    public function leave_counters()
    {
        return $this->hasMany('App\LeaveCounter');
    }

    public function currentLeaveCounter()
    {
        $date_now = Carbon::now('Asia/Manila');
        if ($date_now->month >= 6) {
            $ys = $date_now->year;
            $ye = $date_now->addYear()->year;
        }
        else {
            $ye = $date_now->year;
            $ys = $date_now->subYear()->year;
        }
        
        return $this->leave_counters()->where('year_start', $ys)->where('year_end', $ye)->first();
    }
}
