@props(['post'])

<!-- Barta Card -->
<article class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6">
    <!-- Barta Card Top -->
    <header>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <!-- User Avatar -->
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $post->user->getAvatarUrl(true) }}"
                        alt="" />
                </div>
                <!-- /User Avatar -->

                <!-- User Info -->
                <div class="text-gray-900 flex flex-col min-w-0 flex-1">
                    @if(Request::is('profile') || Request::is('profile/*'))
                    <span class="font-semibold line-clamp-1">
                        {{ $post->user->name }}
                    </span>

                    <span class="text-sm text-gray-500 line-clamp-1">
                        {{ '@' . $post->user->username }}
                    </span>
                    @else
                    <a href="/profile/{{ $post->user->username }}" class="hover:underline font-semibold line-clamp-1">
                        {{ $post->user->name }}
                    </a>

                    <a href="/profile/{{ $post->user->username }}"
                        class="hover:underline text-sm text-gray-500 line-clamp-1">
                        {{ '@' . $post->user->username }}
                    </a>
                    @endif
                </div>
                <!-- /User Info -->
            </div>

            @if(auth()->user()->id == $post->user_id)
            <!-- Card Action Dropdown -->
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
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <a href="/post/{{ $post->uuid }}/edit"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
                            tabindex="-1" id="user-menu-item-0">Edit</a>
                        <form action="/post/{{ $post->uuid }}" class="inline" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ref" value="{{ Request::path() }}">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem" tabindex="-1" id="user-menu-item-1">Delete</button>
                        </form>
                    </div>
                    <!-- /Dropdown menu -->
                </div>
            </div>
            <!-- /Card Action Dropdown -->
            @endif
        </div>
    </header>

    <!-- Content -->
    @if (Request::is('post/*'))
    <div class="py-4 text-gray-700 font-normal space-y-2">
        @if ($post->hasMedia('post-images'))
        <img src="{{ $post->getFirstMediaUrl('post-images') }}"
            class="min-h-auto w-full rounded-lg object-cover max-h-64 md:max-h-72" alt="" />
        @endif

        <p>
            {!! nl2br(e($post->body)) !!}
        </p>
    </div>
    @else
    <a href="/post/{{ $post->uuid }}">
        <div class="py-4 text-gray-700 font-normal space-y-2">
            @if ($post->hasMedia('post-images'))
            <img src="{{ $post->getFirstMediaUrl('post-images') }}"
                class="min-h-auto w-full rounded-lg object-cover max-h-64 md:max-h-72" alt="" />
            @endif

            <p>
                {!! nl2br(e((Str::limit($post->body, 190)))) !!}

                @if (strlen($post->body) > 190)
                <a href="/post/{{ $post->uuid }}" class="text-blue-500 hover:underline">Read more</a>
                @endif
            </p>
        </div>
    </a>
    @endif

    <!-- Date Created & View Stat -->
    <div class="flex items-center gap-2 text-gray-500 text-xs my-2">
        <span class="">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</span>
        @if(Request::is('post/*'))
        <span class="">•</span>
        <span>{{ $post->comments_count ?? $post->comments->count() }} comments</span>
        @endif
        <span class="">•</span>
        <span>{{ $post->views }} views</span>
    </div>

    @if (Request::is('post/*'))
    <hr class="my-6" />

    <!-- Barta Create Comment Form -->
    <form action="/post/{{ $post->uuid }}/comment#comments" method="POST">
        @csrf
        <!-- Create Comment Card Top -->
        <div>
            <div class="flex items-start space-x-3">
                <!-- User Avatar -->
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->getAvatarUrl(true) }}"
                        alt="" />
                </div>
                <!-- /User Avatar -->

                <!-- Auto Resizing Comment Box -->
                <div class="text-gray-700 font-normal w-full">
                    <textarea x-data="{
          resize () {
              $el.style.height = '0px';
              $el.style.height = $el.scrollHeight + 'px'
          }
      }" x-init="resize()" @input="resize()" type="text" name="comment" placeholder="Write a comment..."
                        class="flex w-full h-auto min-h-[40px] px-3 py-2 text-sm bg-gray-100 focus:bg-white border border-sm rounded-lg border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-1 focus:ring-offset-0 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900"
                        required></textarea>
                </div>
            </div>
        </div>

        <!-- Create Comment Card Bottom -->
        <div>
            <!-- Card Bottom Action Buttons -->
            <div class="flex items-center justify-end">
                <button type="submit"
                    class="mt-2 flex gap-2 text-xs items-center rounded-full px-4 py-2 font-semibold bg-gray-800 hover:bg-black text-white">
                    Comment
                </button>
            </div>
            <!-- /Card Bottom Action Buttons -->
        </div>
        <!-- /Create Comment Card Bottom -->
    </form>
    <!-- /Barta Create Comment Form -->

    @else

    <!-- Barta Card Bottom -->
    <footer class="border-t border-gray-200 pt-2">
        <!-- Card Bottom Action Buttons -->
        <div class="flex items-center justify-between">
            <div class="flex gap-8 text-gray-600">
                <!-- Comment Button -->
                <a href="/post/{{ $post->uuid }}#comments" type="button"
                    class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800">
                    <span class="sr-only">Comment</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                    </svg>

                    <p>{{ $post->comments_count ?? $post->comments->count() }}</p>
                </a>
                <!-- /Comment Button -->
            </div>
        </div>
        <!-- /Card Bottom Action Buttons -->
    </footer>
    <!-- /Barta Card Bottom -->
    @endif
</article>
<!-- /Barta Card -->