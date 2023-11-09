<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function edit()
    {
        return view('edit-profile');
    }

    public function update(Request $request)
    {
        $userId = Auth::user()->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:6',
            'bio' => 'nullable|string|max:255',
        ]);

        // If password is not empty, then update it along with other fields
        if ($validated['password'] !== null) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        DB::table('users')->where('id', $userId)->update($validated);

        return redirect('/profile')->with('success', 'Profile updated successfully!');
    }
}