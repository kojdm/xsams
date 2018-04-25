<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;

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

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            // The passwords do not match
            return redirect()->back()->with('error', 'Current password is wrong');
        }

        // Change password
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        return redirect('/home')->with('success', 'Password successfully changed');
    }
}
