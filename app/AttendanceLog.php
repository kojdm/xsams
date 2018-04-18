<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    public function attendance_record() {
        return $this->belongsTo('App\AttendanceRecord');
    }
}
