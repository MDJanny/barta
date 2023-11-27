<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Comment;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(Request $request, string $username = '')
    {
        if ($username === '') {
            $user = $request->user();
        } else {
            $user = User::where('username', $username)->firstOrFail();
        }

        $posts = Post::with('user')
            ->withCount('comments')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCommentsCount = Comment::where('user_id', $user->id)->count();

        return view('profile.index', [
            'user' => $user,
            'posts' => $posts,
            'totalCommentsCount' => $totalCommentsCount,
        ]);
    }

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

        // Update user data and upload avatar if provided
        $user = User::find($userId);
        $user->update($validated);

        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('user-avatars');
        }


        return Redirect::route('profile.index')->with('message', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    /*
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
    */

    /**
     * Search for users.
     */
    public function search()
    {
        $query = request('query');

        if (!$query) {
            return redirect('/');
        }

        $users = User::where('name', 'like', '%' . $query . '%')
            ->orWhere('username', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->get();

        return view('profile.search', [
            'users' => $users,
        ]);
    }
}