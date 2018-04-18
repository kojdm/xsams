<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;

class ChangePasswordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function change() {
        return view('auth.password-change');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if (!(Hash::check($request->get('current-password'), $user->password))) {
            $user->password = bcrypt($request->input('new_password'));
            $user->save();

            return redirect('/home')->with('success', 'Password successfully changed');
        }
        else {
            return back()->with('error', 'Current password is wrong');
        }
    }
}
