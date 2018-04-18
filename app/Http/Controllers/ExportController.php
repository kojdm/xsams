<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Alu;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Get first and last day of current month
        $start_date = date('Y-m-01', strtotime(getDateNow()));
        $end_date = date('Y-m-t', strtotime(getDateNow()));        

        // Retrieve ALUs between start_date and end_date
        $alus = Alu::whereBetween('date', [$start_date, $end_date])->orderBy('date', 'asc')->get();

        $data = [
            'alus' => $alus,
            'start_date' => $start_date,
            'end_date' => $end_date, 
        ];

        return view('alu_forms.export')->with($data);
    }

    public function selectRange(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Get start_date and end_date from form input
        $start_date = date('Y-m-d', strtotime($request->input('start_date')));
        $end_date = date('Y-m-d', strtotime($request->input('end_date')));

        // Retrieve ALUs between start_date and end_date
        $alus = Alu::whereBetween('date', [$start_date, $end_date])->orderBy('date', 'asc')->get();

        $data = [
            'alus' => $alus,
            'start_date' => $start_date,
            'end_date' => $end_date, 
        ];

        return view('alu_forms.export')->with($data);
    }

    public function exportAlus($params)
    {
        $start_date = explode(' ', base64_decode($params))[0];
        $end_date = explode(' ', base64_decode($params))[1];
        
        $alus = Alu::whereBetween('date', [$start_date, $end_date])->orderBy('date', 'asc')->get();

        if (count($alus) < 1) {
            return redirect('/admin/alus/export')->with('error', 'ALUs are empty');
        }
        
        $data = [
            'alus' => $alus,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        Excel::create('alus_'.$start_date."_$end_date", function($excel) use($data) {
            $excel->sheet('alus', function($sheet) use($data) {
                $sheet->loadView('inc.export-alus-table', $data);
            });
        })->export('xls');
    }
}
