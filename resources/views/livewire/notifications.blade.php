<div x-data="{open: false}" class="relative" @click="open = !open">
    <button type=" button" class="rounded-full bg-white p-2 text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
        <span class="sr-only">View notifications</span>
        <!-- &lt;!&ndash; Heroicon name: outline/bell &ndash;&gt; -->
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
    </button>
    <!-- Dropdown menu -->
    <div x-cloak x-show="open" @click.away="open = false" class="absolute w-72 right-0 z-10 mt-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none leading-4" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
        @forelse ($unreadNotifications as $notification)
        <a class="border-b block border-gray-300 p-2 bg-gray-100 hover:bg-gray-200" href="{{ $notification->data['link'] }}" @click="$dispatch('mark-as-read' , {id: '{{ $notification->id }}' })">
            <div>
                {{ $notification->data['message'] }}
            </div>
            <span class="text-xs text-gray-500">
                {{ $notification->created_at->diffForHumans() }}
            </span>
        </a>
        @empty
        <div class="text-gray-500 text-center p-4">
            No unread notifications.
        </div>
        @endforelse

        <a class="border-b block border-gray-300 p-2 text-center bg-gray-300 hover:bg-gray-400/60" href=" {{ route('notifications') }}">
            See all
        </a>
    </div>

    @if($unreadNotifications->count())
    <!-- Badge -->
    <div class="absolute -top-1 -right-1 pointer-events-none">
        <span class="bg-red-500 text-white rounded-full px-2 py-1 text-xs">{{ $unreadNotifications->count() }}</span>
    </div>
    @endif
</div>