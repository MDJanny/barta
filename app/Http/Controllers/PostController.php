<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name as author_name', 'users.username as author_username')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('home', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post' => 'required',
        ]);

        $userId = auth()->user()->id;

        DB::table('posts')->insert([
            'user_id' => $userId,
            'uuid' => Str::uuid(),
            'body' => $validated['post'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('message', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        // Increment view count
        DB::table('posts')
            ->where('uuid', $uuid)
            ->increment('views');

        $post = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name as author_name', 'users.username as author_username')
            ->where('posts.uuid', $uuid)
            ->first();

        if (!$post) {
            abort(404);
        }

        return view('post', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $post = DB::table('posts')
            ->where('uuid', $uuid)
            ->first();

        if (!$post) {
            abort(404);
        }

        if (auth()->user()->id != $post->user_id) {
            abort(403);
        }

        return view('edit-post', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $post = DB::table('posts')
            ->where('uuid', $uuid)
            ->first();

        if (!$post) {
            abort(404);
        }

        if (auth()->user()->id != $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'post' => 'required',
        ]);

        DB::table('posts')
            ->where('uuid', $uuid)
            ->update([
                'body' => $validated['post'],
                'updated_at' => now(),
            ]);

        return redirect('/post/' . $uuid)->with('message', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $post = DB::table('posts')
            ->where('uuid', $uuid)
            ->first();

        if (!$post) {
            abort(404);
        }

        if (auth()->user()->id != $post->user_id) {
            abort(403);
        }

        DB::table('posts')
            ->where('uuid', $uuid)
            ->delete();

        return redirect('/')->with('message', 'Post deleted successfully!');
    }
}