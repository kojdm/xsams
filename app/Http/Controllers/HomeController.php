<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Alu;
use App\LeaveCounter;
use App\LoaForm;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        
        $alus = $user->attendance_record->alus;

        $abs = $alus->where('type', 'A')->where('is_alu_filed', 0)->where('is_expired', 0);
        $absences = [];

        foreach ($abs as $a) {
            if (! $a->hasLoaForm()) {
                $absences[] = $a;
            }
        }

        $lates = $alus->where('type', 'L')->where('is_alu_filed', 0)->where('is_expired', 0);
        $undertimes = $alus->where('type', 'U')->where('is_alu_filed', 0)->where('is_expired', 0);
        $edits = $alus->where('decision', 'Edit');

        // Retrieve ALU Forms that need approval (for Supervisor)
        $alus = Alu::where('is_alu_filed', 1)->where('is_alu_approved', 0)->where('is_confirmed', 1)->whereNull('decision')->get();
        $alus_for_approval = [];

        foreach ($alus as $alu) {
            // Get only ALU Forms of users in same department as supervisor
            if ($alu->attendance_record->user->department->id == $user->department->id) {
                $alus_for_approval[] = $alu->alu_form;
            }
        }

        // Retrieve LOA Forms that need approval (for Supervisor)
        $loa_forms = LoaForm::where('is_approved_supervisor', 0)->where('is_approved_admin', 0)->get();
        $loas_for_approval = [];

        foreach ($loa_forms as $loa) {
            // Get only LOA Forms of users in same department as supervisor
            if ($loa->attendance_record->user->department->id == $user->department->id) {
                $loas_for_approval[] = $loa;
            }
        }

        $data = [
            'user' => $user,
            'absences' => $absences,
            'lates' => $lates,
            'undertimes' => $undertimes,
            'edits' => $edits,
            'alus_for_approval' => $alus_for_approval,
            'loas_for_approval' =>$loas_for_approval,
        ];

        return view('home')->with($data);
    }

    public function updateExpiredAlus() 
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        
        $alus = $user->attendance_record->alus->where('is_expired', 0);

        foreach ($alus as $alu) {
            if (!$alu->hasLoaForm() && strtotime($alu->date_alu_due) < strtotime(getDateNow())) {
                // ALU is already late
                $expired_alu = Alu::find($alu->id);
                $expired_alu->is_expired = true;
                $expired_alu->decision = 'UW';
                $expired_alu->save();
            }
        }

        // Create new Leave Counter for employee if current month is June
        $date_now = Carbon::now('Asia/Manila');
        if ($date_now->month >= 6) {
            if (! LeaveCounter::where('attendance_record_id', $user->attendance_record->id)->where('year_start', $date_now->year)) {
                $new_lc = new LeaveCounter;
                $new_lc->attendance_record_id = $user->attendance_record->id;
                $new_lc->year_start = $date_now->year;
                $new_lc->year_end = $date_now->addYear()->year;
                $new_lc->save();
            }
        }

        return redirect('/home');
    }
}
