<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alu extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public function attendance_record() 
    {
        return $this->belongsTo('App\AttendanceRecord');
    }
    
    public function alu_form() 
    {
        return $this->hasOne('App\AluForm');
    }

    public function loa_form()
    {
        return $this->belongsToMany('App\LoaForm');
    }

    public function hasLoaForm()
    {
        return null !== $this->loa_form()->first();
    }

    public function getLoaForm()
    {
        return $this->loa_form()->first();
    }
}
