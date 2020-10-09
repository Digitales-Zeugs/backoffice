<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login', 'auth']);
    }

    public function login(Request $request)
    {
        return view('auth.login')->withInput('username');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:32',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['username', 'password']);

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }

        return back();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
