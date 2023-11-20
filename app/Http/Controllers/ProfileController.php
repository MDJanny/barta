<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function index(Request $request, string $username = '')
    {
        if ($username === '') {
            $user = $request->user();
        } else {
            $user = DB::table('users')->where('username', $username)->first();
        }

        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
            ->select('posts.*', 'users.name as author_name', 'users.username as author_username', DB::raw('count(comments.id) as comment_count'))
            ->groupBy('posts.id')
            ->having('posts.user_id', '=', $user->id)
            ->orderBy('posts.created_at', 'desc')
            ->get();

        $commentsCount = DB::table('comments')->where('user_id', $user->id)->count();

        return view('profile.index', [
            'user' => $user,
            'posts' => $posts,
            'commentsCount' => $commentsCount,
        ]);
    }

    /**
     * Display the specified user's profile.
     */
    // public function show(Request $request, string $username): View
    // {
    //     $user = DB::table('users')->where('username', $username)->first();
    //     $posts = DB::table('posts')
    //         ->join('users', 'posts.user_id', '=', 'users.id')
    //         ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
    //         ->select('posts.*', 'users.name as author_name', 'users.username as author_username', DB::raw('count(comments.id) as comment_count'))
    //         ->groupBy('posts.id')
    //         ->having('posts.user_id', '=', $user->id)
    //         ->orderBy('posts.created_at', 'desc')
    //         ->get();
    //     $commentsCount = DB::table('comments')->where('user_id', $user->id)->count();

    //     return view('profile.index', [
    //         'user' => $user,
    //         'posts' => $posts,
    //         'commentsCount' => $commentsCount,
    //     ]);
    // }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $userId = Auth::user()->id;
        $validated = $request->validated();

        // If password is not empty, then update it along with other fields
        if ($validated['password'] !== null) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        DB::table('users')->where('id', $userId)->update($validated);

        return Redirect::route('profile.index')->with('message', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}