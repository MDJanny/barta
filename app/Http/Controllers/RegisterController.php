<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:25',
            'username' => 'required|min:3|max:15|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:20|confirmed',
        ]);

        DB::table('users')->insert([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/login')->with('success', 'Registration successfull! You can now login.');
    }
}