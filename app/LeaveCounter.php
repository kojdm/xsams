<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveCounter extends Model
{
    public function attendance_record()
    {
        return $this->belongsTo('App\AttendanceRecord');
    }
}
