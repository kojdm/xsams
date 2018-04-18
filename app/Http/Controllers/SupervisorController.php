<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\AluForm;
use App\LoaForm;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function usersIndex()
    {
        auth()->user()->authorizeRoles('supervisor');

        $users = User::where('department_id', auth()->user()->department_id)->get();

        return view('users.index')->with('users', $users);
    }

    public function aluFormRecommendation($params)
    {
        auth()->user()->authorizeRoles('supervisor');

        $alu_form_id = explode(' ', base64_decode($params))[0];
        $alu_form = AluForm::find($alu_form_id);

        // Check if alu form exists in database and is not yet approved
        if ($alu_form == null || $alu_form->alu->is_alu_approved == true) {
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
        auth()->user()->authorizeRoles('supervisor');

        $this->validate($request, [
            'recommendation' => 'required',
            'supervisor_remarks' => 'required_if:recommendation,==,Edit|max:191',            
        ]);

        $alu_form = AluForm::find($alu_form_id);

        // Send back to employee for edits
        if ($request->input('recommendation') == 'Edit') {
            $alu_form->alu->decision = $request->input('recommendation');
            $alu_form->alu->save();
            $alu_form->supervisor_remarks = $request->input('supervisor_remarks');
            $alu_form->save();

            return redirect('/home')->with('success', 'ALU Form sent back for edits');
        }
        
        $alu_form->recommendation = $request->input('recommendation');
        if ($request->input('supervisor_remarks')) {
            $alu_form->supervisor_remarks = $request->input('supervisor_remarks');
        }
        $alu_form->save();
        $alu_form->alu->is_alu_approved = true;
        $alu_form->alu->save();

        return redirect('/home')->with('success', 'ALU Form decision saved');
    }

    public function loaFormRecommendation($params)
    {
        auth()->user()->authorizeRoles('supervisor');

        $loa_form_id = explode(' ', base64_decode($params))[0];
        $loa_form = LoaForm::find($loa_form_id);

        // Check if loa form exists in database and is not yet approved
        if ($loa_form == null || $loa_form->is_approved_supervisor == true || $loa_form->is_approved_admin == true) {
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
        auth()->user()->authorizeRoles('supervisor');

        $this->validate($request, [
            'supervisor_remarks' => 'required|max:191',            
        ]);

        $loa_form = LoaForm::find($loa_form_id);
        
        $loa_form->supervisor_remarks = $request->input('supervisor_remarks');
        $loa_form->is_approved_supervisor = true;
        $loa_form->save();

        return redirect('/home')->with('success', 'LOA Form approved');
    }
}
