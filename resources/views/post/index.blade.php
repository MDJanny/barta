@extends('layouts.app')

@section('title', 'Post Details')
@section('content')
<!-- Newsfeed -->
<section id="newsfeed" class="space-y-6">
    <x-post-card :post="$post" />

    <hr />
    @if ($post->comments->count() > 0)
    <div class="flex flex-col space-y-6" id="comments">
        <h1 class="text-lg font-semibold">Comments ({{ $post->comments->count() }})</h1>

        <!-- Barta User Comments Container -->
        <article
            class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-2 sm:px-6 min-w-full divide-y">
            <!-- Comments -->

            @foreach ($post->comments as $comment)
            <div class="py-4">
                <!-- Barta User Comments Top -->
                <header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    src="{{ $comment->user->getAvatarUrl(true) }}" alt="" />
                            </div>
                            <!-- /User Avatar -->
                            <!-- User Info -->
                            <div class="text-gray-900 flex flex-col min-w-0 flex-1">
                                <a href="/profile/{{ $comment->user->username }}"
                                    class="hover:underline font-semibold line-clamp-1">
                                    {{ $comment->user->name }}
                                </a>

                                <a href="/profile/{{ $comment->user->username }}"
                                    class="hover:underline text-sm text-gray-500 line-clamp-1">
                                    {{ '@' . $comment->user->username }}
                                </a>
                            </div>
                            <!-- /User Info -->
                        </div>

                        @if(auth()->user()->id === $comment->user->id)
                        <!-- Action Dropdown -->
                        <div class="flex flex-shrink-0 self-center" x-data="{ open: false }">
                            <div class="relative inline-block text-left">
                                <div>
                                    <button @click="open = !open" type="button"
                                        class="-m-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600"
                                        id="menu-0-button">
                                        <span class="sr-only">Open options</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path
                                                d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Dropdown menu -->
                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                    tabindex="-1">
                                    <a href="/post/{{ $post->uuid }}/comment/{{ $comment->uuid }}/edit"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
                                        tabindex="-1" id="user-menu-item-0">Edit</a>
                                    <form action="/post/{{ $post->uuid }}/comment/{{ $comment->uuid }}#comments"
                                        class="inline" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="ref" value="{{ Request::path() }}">
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this post?')"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem" tabindex="-1" id="user-menu-item-1">Delete</button>
                                    </form>
                                </div>
                                <!-- /Dropdown menu -->
                            </div>
                        </div>
                        <!-- /Action Dropdown -->
                        @endif
                    </div>
                </header>

                <!-- Content -->
                <div class="py-4 text-gray-700 font-normal">
                    <p>{!! nl2br(e($comment->body)) !!}</p>
                </div>

                <!-- Date Created -->
                <div class="flex items-center gap-2 text-gray-500 text-xs">
                    <span class="">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach

            <!-- /Comments -->
        </article>
        <!-- /Barta User Comments -->
    </div>
    @else
    <div>
        <p class="text-gray-500 text-center">No comments yet.</p>
    </div>
    @endif
</section>
<!-- /Newsfeed -->
@endsection