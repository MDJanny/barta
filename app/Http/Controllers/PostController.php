<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all posts along with the author's name, username, and comment count
        $posts = Post::with('user')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
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

        $post = Post::create([
            'user_id' => $userId,
            'uuid' => Str::uuid(),
            'body' => $validated['post'],
        ]);

        if ($request->hasFile('image')) {
            $post->addMediaFromRequest('image')
                ->toMediaCollection('post-images');
        }

        return back()->with('message', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        // Increment view count
        Post::where('uuid', $uuid)
            ->increment('views');

        $post = Post::with('user', 'comments.user')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('post.index', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $post = Post::where('uuid', $uuid)
            ->firstOrFail();

        if (auth()->user()->id != $post->user->id) {
            abort(403);
        }

        return view('post.edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $post = Post::where('uuid', $uuid)
            ->firstOrFail();

        if (auth()->user()->id != $post->user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'post' => 'required',
        ]);

        $post->body = $validated['post'];
        $post->save();

        return redirect('/post/'.$uuid)->with('message', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $post = Post::where('uuid', $uuid)
            ->firstOrFail();

        if (auth()->user()->id != $post->user->id) {
            abort(403);
        }

        $post->delete();

        if (request()->ref == 'post/'.$uuid) {
            return redirect('/')->with('message', 'Post deleted successfully!');
        } else {
            return back()->with('message', 'Post deleted successfully!');
        }
    }
}
