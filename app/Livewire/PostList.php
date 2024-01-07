<?php

namespace App\Livewire;

use App\Events\PostLiked;
use App\Models\Post;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PostList extends Component
{
    public $amount = 10;

    public ?User $user = null;

    #[Computed()]
    public function posts()
    {
        if ($this->user) {
            return $this->user->posts()->latest()->take($this->amount)->get();
        } else {
            return Post::latest()->take($this->amount)->get();
        }
    }

    public function render()
    {
        return view('livewire.post-list');
    }

    public function loadMore()
    {
        $this->amount += 10;
    }

    public function toggleLike($postId)
    {
        $post = Post::find($postId);

        if ($post->isLikedByUser(auth()->user())) {
            $post->unlike(auth()->user());
        } else {
            $like = $post->like(auth()->user());

            if (auth()->user()->id !== $post->user->id) {
                PostLiked::dispatch($like);
            }
        }
    }
}
