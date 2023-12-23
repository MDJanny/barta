<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class Posts extends Component
{
    public $amount = 10;

    public function render()
    {
        $posts = Post::latest()->take($this->amount)->get();
        return view('livewire.posts', compact('posts'));
    }

    public function loadMore()
    {
        $this->amount += 10;
    }
}
