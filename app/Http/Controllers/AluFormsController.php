<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Alu;
use App\AluForm;

class AluFormsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        
        // Get current month
        $month_now = date('F Y', strtotime(getDateNow()));

        $alus = Alu::where('attendance_record_id', $user->attendance_record->id)->where('is_alu_filed', 1)->whereYear('date', date('Y', strtotime($month_now)))->whereMonth('date', date('m', strtotime($month_now)))->orderBy('updated_at', 'desc')->get();

        $alu_forms = [];
        foreach ($alus as $alu) {
            if ($alu->alu_form) {
                array_push($alu_forms, $alu->alu_form);
            }
        }

        $data = [
            'alu_forms' => $alu_forms,
            'month' => $month_now
        ];

        return view('alu_forms.index')->with($data);
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

        return redirect("/aluforms/display/$month");
    }

    public function indexWithRange($month)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if ($month == 'all') {
            $month = 'all months';
            $alus = $user->attendance_record->alus->where('is_alu_filed', 1)->sortByDesc('updated_at');
        }
        else {
            $month = date('F Y', strtotime($month));            
            $alus = Alu::where('attendance_record_id', $user->attendance_record->id)->where('is_alu_filed', 1)->whereYear('date', date('Y', strtotime($month)))->whereMonth('date', date('m', strtotime($month)))->orderBy('updated_at', 'desc')->get();
        }

        $alu_forms = [];
        foreach ($alus as $alu) {
            if ($alu->alu_form) {
                array_push($alu_forms, $alu->alu_form);
            }
        }
        
        $data = [
            'alu_forms' => $alu_forms,
            'month' => $month,
        ];

        return view('alu_forms.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        return view('alu_forms.create')->with('user', $user);
    }

    public function file($params)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $alu_id = explode(' ', base64_decode($params))[0];

        $alu = Alu::find($alu_id);

        // Check if alu exists in database and has decision NULL
        if ($alu == null || $alu->decision != null) {
            return abort(404);
        }

        // Check if ALU has already expired
        if ($alu->is_expired) {
            return redirect('/home')->with('error', 'ALU Form has already expired');
        }
        elseif (!$alu->hasLoaForm() && strtotime($alu->date_alu_due) < strtotime(getDateNow())) {
            $alu->is_expired = true;
            $alu->decision = 'UW';
            $alu->save();

            return redirect('/home')->with('error', 'ALU Form has already expired');            
        }

        // Check for correct user (if logged in user matches alu's user)
        if ($user_id !== $alu->attendance_record->user->id) {
            return redirect('/home')->with('error', 'Unauthorized Page');
        }

        // Check if alu form has already been filed for this ALU
        if ($alu->is_alu_filed) {
            return redirect('/home')->with('error', 'ALU Form has already been filed');
        }

        $data = [
            'user' => $user,
            'alu' => $alu,
        ];
        return view('alu_forms.file')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'alu_id' => 'required',
            'date_filed' => 'required|date',
            'reason' => 'required',
        ]);

        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        
        // Store ALU Form
        $alu_form = new AluForm;
        $alu_form->alu_id = $request->input('alu_id');
        $alu_form->date_filed = $request->input('date_filed');
        $alu_form->reason = $request->input('reason');
        $alu_form->save();

        // Update ALU attributes
        $alu = Alu::find($request->input('alu_id'));
        $alu->is_alu_filed = true;
        if ($user->hasRole('supervisor')) {
            $alu->is_alu_approved = true;
        }
        $alu->save();

        return redirect('/home')->with('success', 'ALU Form filed');
    }

    public function storeAdvanced(Request $request)
    {
        $this->validate($request, [
            'shift_start' => 'required',
            'shift_end' => 'required',
            'date_filed' => 'required|date',
            'type' => 'required',
            'date' => 'required|date',
            'time' => 'required_if:type,L,U|after:shift_start|before:shift_end',
            'reason' => 'required',
        ]);

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        // Create ALU model
        $alu = new Alu;
        $alu->attendance_record_id = $user->attendance_record->id;
        $alu->type = $request->input('type');
        $alu->date = $request->input('date');
        if ($request->input('type') !== 'A') {
            $alu->time = date('H:i:s', strtotime($request->input('time')));
        }
        $alu->date_alu_due = date('Y-m-d', strtotime($request->input('date'). ' + 7 days'));
        $alu->is_alu_filed = true;
        if ($user->hasRole('supervisor')) {
            $alu->is_alu_approved = true;
        }
        $alu->is_confirmed = false;
        $alu->save();

        // Store ALU Form
        $alu_form = new AluForm;
        $alu_form->alu_id = $alu->id;
        $alu_form->date_filed = $request->input('date_filed');
        $alu_form->reason = $request->input('reason');
        $alu_form->save();

        return redirect('/aluforms')->with('success', 'ALU Form filed');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($params)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $alu_form_id = explode(' ', base64_decode($params))[0];

        $alu_form = AluForm::find($alu_form_id);

        // Check if alu form exists in database
        if ($alu_form == null) {
            return abort(404);
        }

        // Check for correct user (if logged in user matches alu's user)
        if ($user_id !== $alu_form->alu->attendance_record->user->id) {
            return abort(404);
        }

        $data = [
            'user' => $user,
            'alu_form' => $alu_form,
            'alu' => $alu_form->alu,
        ];

        return view('alu_forms.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($params)
    {
        $alu_form_id = explode(' ', base64_decode($params))[0];
        $alu_form = AluForm::find($alu_form_id);
        $user_id = auth()->user()->id;

        // Check if alu form exists in database and has decision 'Edit'
        if ($alu_form == null || $alu_form->alu->decision !== 'Edit') {
            return abort(404);
        }

        // Check for correct user (if logged in user matches alu's user)
        if ($user_id !== $alu_form->alu->attendance_record->user->id) {
            return redirect('/home')->with('error', 'Unauthorized Page');
        }

        $alu = $alu_form->alu;
        $user = $alu_form->alu->attendance_record->user;

        $data = [
            'alu_form' => $alu_form,
            'alu' => $alu,
            'user' => $user,
        ];

        return view('alu_forms.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $alu_form_id)
    {
        $this->validate($request, [
            'old_reason' => 'required',
            'reason' => 'required|different:old_reason',
        ]);

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $alu_form = AluForm::find($alu_form_id);
        $alu_form->reason = $request->input('reason');
        $alu_form->supervisor_remarks = null;
        $alu_form->admin_remarks = null;        
        $alu_form->save();

        $alu_form->alu->decision = NULL;
        if ($user->hasRole('supervisor')) {
            $alu_form->alu->is_alu_approved = true;
        }
        $alu_form->alu->save();

        return redirect('/aluforms')->with('success', 'ALU Form resent');
    }

    public function expiredAlus()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $expired_alus = Alu::where('is_expired', 1)->orderBy('date', 'desc')->get();

        return view('alu_forms.expired')->with('alus', $expired_alus);
    }
}
