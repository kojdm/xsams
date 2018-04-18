<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alu;
use App\AluForm;
use App\LoaForm;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alus = Alu::where('is_alu_filed', 1)->where('is_alu_approved', 1)->where('is_confirmed', 1)->whereNull('decision')->get();
        $alu_forms = [];

        foreach ($alus as $alu) {
            $alu_forms[] = $alu->alu_form;
        }

        $loa_forms = LoaForm::where('is_approved_supervisor', 1)->where('is_approved_admin', 0)->get();

        $data = [
            'alu_forms' => collect($alu_forms),
            'loa_forms' => $loa_forms,
        ];

        return view('admin')->with($data);
    }
}
