<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function postlogin(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        return back()->with('error', 'Invalid credentials!')->withInput();
    }

    public function register()
    {
        return view('register');
    }

    public function postregister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:35',
            'username' => 'required|min:3|max:35|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6|max:20|confirmed',
        ]);

        $user = new User();

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        $user->save();

        return redirect('/login')->with('success', 'Registration successfull! You can now login.');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
