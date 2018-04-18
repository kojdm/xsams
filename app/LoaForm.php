<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoaForm extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function attendance_record() 
    {
        return $this->belongsTo('App\AttendanceRecord');
    }

    public function alus()
    {
        return $this->belongsToMany('App\Alu');
    }

    public function inclusiveDates()
    {
        $inclusive_dates = '';
        foreach ($this->alus()->get() as $alu) {
            $inclusive_dates .= date('m/d/Y', strtotime($alu->date)) . ', ';
        }

        return $inclusive_dates;
    }
}
