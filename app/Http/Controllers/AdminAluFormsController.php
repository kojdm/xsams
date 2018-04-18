<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alu;
use App\AluForm;

class AdminAluFormsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $month_now = date('F Y', strtotime(getDateNow()));

        $alus = Alu::where('is_alu_filed', 1)->where('is_alu_approved', 1)->whereYear('date', date('Y', strtotime($month_now)))->whereMonth('date', date('m', strtotime($month_now)))->get();

        $alu_forms_completed = [];
        foreach ($alus as $alu) {
            if ($alu->decision != null && $alu->decision != 'Edit') {
                array_push($alu_forms_completed, $alu->alu_form);
            }
        }

        $data = [
            'alu_forms' => $alu_forms_completed,
            'month' => $month_now,
        ];

        return view('alu_forms.index-admin')->with($data);
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

        return redirect("/admin/aluforms/display/$month");
    }

    public function indexWithRange($month)
    {
        if ($month == 'all') {
            $month = 'all months';
            $alus = Alu::where('is_alu_filed', 1)->where('is_alu_approved', 1)->get();
        }
        else {
            $month = date('F Y', strtotime($month));            
            $alus = Alu::where('is_alu_filed', 1)->where('is_alu_approved', 1)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->get();
        }

        $alu_forms_completed = [];
        foreach ($alus as $alu) {
            if ($alu->decision != null && $alu->decision != 'Edit') {
                array_push($alu_forms_completed, $alu->alu_form);
            }
        }
        
        $data = [
            'alu_forms' => $alu_forms_completed,
            'month' => $month,
        ];

        return view('alu_forms.index-admin')->with($data);
    }

    public function pendingAluForms()
    {
        $alus = Alu::where('is_alu_filed', 1)->where('is_confirmed', 1)->get();

        $alu_forms_pending = [];
        $alu_forms_edit = [];
        foreach ($alus as $alu) {
            if ($alu->is_alu_approved == true && $alu->decision == null) {
                array_push($alu_forms_pending, $alu->alu_form);
            }
            elseif ($alu->decision == 'Edit') {
                array_push($alu_forms_edit, $alu->alu_form);                
            }
        }

        $data = [
            'alu_forms_pending' => $alu_forms_pending,
            'alu_forms_edit' => $alu_forms_edit,                            
        ];

        return view('alu_forms.pending-admin')->with($data);
    }

    public function show($params)
    {
        $alu_form_id = explode(' ', base64_decode($params))[0];

        $alu_form = AluForm::find($alu_form_id);

        // Check if alu form exists in database
        if ($alu_form == null) {
            return abort(404);
        }

        $user = $alu_form->alu->attendance_record->user;

        $data = [
            'user' => $user,
            'alu_form' => $alu_form,
            'alu' => $alu_form->alu,
        ];

        return view('alu_forms.show')->with($data);
    }

    public function aluFormDecision($params) 
    {
        $alu_form_id = explode(' ', base64_decode($params))[0];
        $alu_form = AluForm::find($alu_form_id);

        // Check if alu form exists in database, has been approved, has occurred, and has decision NULL
        if ($alu_form == null || $alu_form->alu->is_alu_approved == false || $alu_form->alu->is_confirmed == false || $alu_form->alu->decision != null) {
            return abort(404);
        }

        $alu = $alu_form->alu;
        $user = $alu_form->alu->attendance_record->user;

        $data = [
            'alu_form' => $alu_form,
            'alu' => $alu,
            'user' => $user,
        ];

        return view('alu_forms.decision')->with($data);
    }

    public function aluFormUpdate(Request $request, $alu_form_id) 
    {
        
        $this->validate($request, [
            'decision' => 'required',
            'admin_remarks' => 'required_if:decision,==,Edit|max:191'
        ]);

        $alu_form = AluForm::find($alu_form_id);

        // Send back to employee for edits
        if ($request->input('decision') == 'Edit') {
            $alu_form->alu->decision = $request->input('decision');
            $alu_form->alu->is_alu_approved = false;
            $alu_form->alu->save();
            $alu_form->admin_remarks = $request->input('admin_remarks');
            $alu_form->recommendation = null;                                
            $alu_form->supervisor_remarks = null;
            $alu_form->save();

            return redirect('/admin')->with('success', 'ALU Form sent back for edits');
        }
        
        $alu_form->alu->decision = $request->input('decision');
        $alu_form->alu->save();
        if ($request->input('admin_remarks')) {
            $alu_form->admin_remarks = $request->input('admin_remarks');
            $alu_form->save();
        }

        return redirect('/admin')->with('success', 'ALU Form decision saved');
    }
}
