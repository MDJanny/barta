<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Notifications extends Component
{
    public $userId;

    public function mount()
    {
        $this->userId = auth()->id();
    }

    public function getListeners()
    {
        return [
            'echo-private:user.'.$this->userId.',CommentAdded' => 'render',
            'echo-private:user.'.$this->userId.',PostLiked' => 'render',
        ];
    }

    #[On('mark-as-read')]
    public function markAsRead($id)
    {
        auth()->user()->notifications->where('id', $id)->first()->markAsRead();
    }

    public function render()
    {
        $unreadNotifications = auth()->user()->unreadNotifications;

        return view('livewire.notifications', compact('unreadNotifications'));
    }
}
