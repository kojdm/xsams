<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaForm;
use App\LeaveCounter;

class AdminLoaFormsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $month_now = date('F Y', strtotime(getDateNow()));

        $loa_forms = LoaForm::where('is_approved_supervisor', 1)->where('is_approved_admin', 1)->whereYear('date_filed', date('Y', strtotime($month_now)))->whereMonth('date_filed', date('m', strtotime($month_now)))->get();

        $data = [
            'loa_forms' => $loa_forms,
            'month' => $month_now,
        ];

        return view('loa_forms.index')->with($data);
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

        return redirect("/admin/loaforms/display/$month");
    }

    public function indexWithRange($month)
    {
        if ($month == 'all') {
            $month = 'all months';
            $loa_forms = LoaForm::where('is_approved_supervisor', 1)->where('is_approved_admin', 1)->get();
        }
        else {
            $month = date('F Y', strtotime($month));            
            $loa_forms = LoaForm::where('is_approved_supervisor', 1)->where('is_approved_admin', 1)->whereYear('date_filed', date('Y', strtotime($month)))->whereMonth('date_filed', date('m', strtotime($month)))->get();
        }
        
        $data = [
            'loa_forms' => $loa_forms,
            'month' => $month,
        ];

        return view('loa_forms.index')->with($data);
    }

    public function pendingLoaForms()
    {
        $loa_forms = LoaForm::where('is_approved_supervisor', 1)->where('is_approved_admin', 0)->get();

        $data = [
            'loa_forms' => $loa_forms,                           
        ];

        return view('loa_forms.pending-admin')->with($data);
    }

    public function show($params)
    {
        $loa_form_id = explode(' ', base64_decode($params))[0];
        $loa_form = LoaForm::find($loa_form_id);

        // Check if alu form exists in database
        if ($loa_form == null) {
            return abort(404);
        }

        $user = $loa_form->attendance_record->user;

        $data = [
            'user' => $user,
            'loa_form' => $loa_form,
        ];

        return view('loa_forms.show')->with($data);
    }
    
    public function loaFormDecision($params) 
    {
        $loa_form_id = explode(' ', base64_decode($params))[0];
        $loa_form = LoaForm::find($loa_form_id);

        // Check if loa form exists in database, has been approved by supervisor, and has not yet been approved by admin
        if ($loa_form == null || $loa_form->is_approved_supervisor == false || $loa_form->is_approved_admin == true) {
            return abort(404);
        }

        $user = $loa_form->attendance_record->user;

        $data = [
            'loa_form' => $loa_form,
            'user' => $user,
        ];

        return view('loa_forms.decision')->with($data);
    }

    public function loaFormUpdate(Request $request, $loa_form_id)
    {
        $this->validate($request, [
            'admin_remarks' => 'required|max:191',            
        ]);

        $loa_form = LoaForm::find($loa_form_id);
        $user = $loa_form->attendance_record->user;
        
        $loa_form->admin_remarks = $request->input('admin_remarks');
        $loa_form->is_approved_admin = true;
        $loa_form->save();

        // Deduct from Leave Counter of user
        $lc = LeaveCounter::find($user->attendance_record->id);
        if ($loa_form->classification == "VLP") {
            $lc->vlp_count -= $loa_form->num_work_days;
        }
        elseif ($loa_form->classification == "SPL") {
            $lc->spl_count -= $loa_form->num_work_days;
        }
        elseif ($loa_form->classification == "GL") {
            $lc->GL_count -= $loa_form->num_work_days;
        }
        elseif ($loa_form->classification == "VAWCL") {
            $lc->vawcl_count -= $loa_form->num_work_days;
        }
        else {
            $lc->sl_count -= $loa_form->num_work_days;
        }
        $lc->save();

        return redirect('/admin')->with('success', 'LOA Form approved');
    }
}
