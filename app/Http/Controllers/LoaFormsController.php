<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaForm;
use App\Alu;
use App\LeaveCounter;
use App\User;

class LoaFormsController extends Controller
{
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

        $loa_forms = LoaForm::where('attendance_record_id', $user->attendance_record->id)->whereYear('date_filed', date('Y', strtotime($month_now)))->whereMonth('date_filed', date('m', strtotime($month_now)))->orderBy('updated_at', 'desc')->get();

        $data = [
            'loa_forms' => $loa_forms,
            'month' => $month_now
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

        return redirect("/loaforms/display/$month");
    }

    public function indexWithRange($month)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if ($month == 'all') {
            $month = 'all months';
            $loa_forms = $user->attendance_record->loa_forms->sortByDesc('updated_at');
        }
        else {
            $month = date('F Y', strtotime($month));            
            $loa_forms = LoaForm::where('attendance_record_id', $user->attendance_record->id)->whereYear('date_filed', date('Y', strtotime($month)))->whereMonth('date_filed', date('m', strtotime($month)))->orderBy('created_at', 'desc')->get();            
        }
        
        $data = [
            'loa_forms' => $loa_forms,
            'month' => $month,
        ];

        return view('loa_forms.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'user' => auth()->user(),
        ];
        return view('loa_forms.create')->with($data);
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
            'date_filed' => 'required|date',
            'type' => 'required',
            'inclusive_dates' => 'required',
            'num_work_days' => 'required',
            'classification' => 'required',
            'reason' => 'sometimes|required',
            'med_certificate' => 'required_if:type,==,sick|file|image'
        ]);

        $user = auth()->user();

        // Create and store new LOA Form
        $loa_form = new LoaForm;
        $loa_form->attendance_record_id = $user->attendance_record->id;
        $loa_form->date_filed = $request->input('date_filed');
        $loa_form->type = $request->input('type');
        $loa_form->num_work_days = $request->input('num_work_days');
        $loa_form->classification = $request->input('classification');
        if ($request->input('reason')) {
            $loa_form->reason = $request->input('reason');
        }

        // Attach medical certificate to LOA Form
        if ($request->file('med_certificate')) {
            // Get filename with extension
            $filenameWithExt = $request->file('med_certificate')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('med_certificate')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload file
            $path = $request->file('med_certificate')->storeAs('public/med_certificates', $fileNameToStore);

            $loa_form->med_certificate = $fileNameToStore;
        }

        // Make LOA Form approved if user who submitted it is a supervisor
        if ($user->hasRole('supervisor')) {
            $loa_form->is_approved_supervisor = true;
        }
        $loa_form->save();

        // Get inclusive dates from LOA Form and transform into sorted array
        $dates_input = explode(", ", $request->input('inclusive_dates'));
        $dates = [];
        foreach ($dates_input as $date_input) {
            $dates[] = date('Y-m-d', strtotime($date_input));
        }
        usort($dates, [$this, "date_sort"]);

        // If type is Regular LOA
        if ($request->input('type') == "regular") {

            // Create new ALU(absences) for each date in the LOA Form
            foreach ($dates as $date) {
                $alu = new Alu;
                $alu->attendance_record_id = $user->attendance_record->id;
                $alu->type = 'A';
                $alu->date = $date;
                $alu->date_alu_due = date('Y-m-d', strtotime($date. ' + 7 days'));
                $alu->is_confirmed = false;
                $alu->save();
                $alu->loa_form()->attach($loa_form);
            }

            return redirect('/loaforms')->with('success', 'LOA Form filed');
        } 

        // If type is Sick LOA
        // Search through existing ALUs for dates matching the LOA Form
        foreach ($dates as $date) {
            $absence = Alu::where('attendance_record_id', $user->attendance_record->id)->where('type', 'A')->where('date', $date)->first();

            // Attach LOA to ALU absence
            if ($absence) {
                $absence->loa_form()->attach($loa_form);
            }
            else {
                // Create new ALU(absence) if no exisiting ALU is found
                $alu = new Alu;
                $alu->attendance_record_id = $user->attendance_record->id;
                $alu->type = 'A';
                $alu->date = $date;
                $alu->date_alu_due = date('Y-m-d', strtotime($date. ' + 7 days'));
                $alu->is_confirmed = false;
                $alu->save();
                $alu->loa_form()->attach($loa_form);
            }
        }

        return redirect('/loaforms')->with('success', 'LOA Form filed');
    }

    private static function date_sort($a, $b) {
        return strtotime($a) - strtotime($b);
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
        $loa_form_id = explode(' ', base64_decode($params))[0];

        $loa_form = LoaForm::find($loa_form_id);

        // Check if alu form exists in database
        if ($loa_form == null) {
            return abort(404);
        }

        // Check for correct user (if logged in user matches alu's user)
        if ($user_id !== $loa_form->attendance_record->user->id) {
            return abort(404);
        }

        $data = [
            'user' => $user,
            'loa_form' => $loa_form,
        ];

        return view('loa_forms.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
