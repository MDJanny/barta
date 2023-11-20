<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $postId = DB::table('posts')->where('uuid', $postUuid)->value('id');

        DB::table('comments')->insert([
            'user_id' => $userId,
            'post_id' => $postId,
            'uuid' => Str::uuid(),
            'body' => $validated['comment'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
        $comment = DB::table('comments')->where('uuid', $commentUuid)->first();

        if (!$comment) {
            abort(404);
        }

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
        $comment = DB::table('comments')->where('uuid', $commentUuid)->first();

        if (!$comment) {
            abort(404);
        }

        // Check if the user is the owner of the comment
        if ($comment->user_id !== auth()->user()->id) {
            abort(403);
        }

        DB::table('comments')->where('uuid', $commentUuid)->update([
            'body' => $validated['comment'],
            'updated_at' => now(),
        ]);

        return redirect('/post/' . $postUuid)->with('message', 'Comment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $postUuid, string $commentUuid)
    {
        // Check if the comment exists
        $comment = DB::table('comments')->where('uuid', $commentUuid)->first();

        if (!$comment) {
            abort(404);
        }

        // Check if the user is the owner of the comment
        if ($comment->user_id !== auth()->user()->id) {
            abort(403);
        }

        DB::table('comments')->where('uuid', $commentUuid)->delete();

        return back()->with('message', 'Comment deleted successfully!');
    }
}