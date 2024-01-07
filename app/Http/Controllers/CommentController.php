<?php

namespace App\Http\Controllers;

use App\Events\CommentAdded;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, string $postUuid)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        $userId = auth()->user()->id;
        $postId = Post::where('uuid', $postUuid)->firstOrFail()->id;

        $comment = Comment::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'uuid' => Str::uuid(),
            'body' => $validated['comment'],
        ]);

        if ($comment->post->user_id !== $userId) {
            CommentAdded::dispatch($comment);
        }

        return back()->with('message', 'Comment created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $postUuid, string $commentUuid)
    {
        // Check if the comment exists
        $comment = Comment::where('uuid', $commentUuid)->firstOrFail();

        // Check if the user is the owner of the comment
        if ($comment->user_id !== auth()->user()->id) {
            abort(403);
        }

        return view('comment.edit', [
            'post_uuid' => $postUuid,
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $postUuid, string $commentUuid)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        // Check if the comment exists
        $comment = Comment::where('uuid', $commentUuid)->firstOrFail();

        // Check if the user is the owner of the comment
        if ($comment->user_id !== auth()->user()->id) {
            abort(403);
        }

        $comment->update([
            'body' => $validated['comment'],
        ]);

        return redirect('/post/'.$postUuid.'#comments')->with('message', 'Comment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $postUuid, string $commentUuid)
    {
        // Check if the comment exists
        $comment = Comment::where('uuid', $commentUuid)->firstOrFail();

        // Check if the user is the owner of the comment
        if ($comment->user_id !== auth()->user()->id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('message', 'Comment deleted successfully!');
    }
}
