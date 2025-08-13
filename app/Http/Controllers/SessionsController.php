<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($attributes)) {
            session()->regenerate();

            session(['login_time' => Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i')]);
            
            return redirect('/')->with('success', 'Login berhasil!');
        } else {

            return back()->withErrors(['email' => 'Email or password invalid.']);
        }
    }

    public function destroy()
    {

        Auth::logout();
        Session::flash('message', 'Anda telah berhasil logout.');
        return redirect('/');
    }
}
