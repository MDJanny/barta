@extends('layouts.app')

@section('title', 'All Notifications')
@section('content')

<h1 class="text-xl font-semibold leading-7 text-gray-900">
    All Notifications
</h1>

@if ($notifications->isEmpty())
<div class="text-gray-500 text-center p-4">
    No notifications.
</div>
@else
<ul class="rounded overflow-clip" x-data>
    @foreach ($notifications as $notification)
    <li>
        @if ($notification->read_at)
        <a href="{{ $notification->data['link'] }}"
            class="border-b border-gray-300 p-2 hover:bg-gray-300 flex justify-between">
            <span>
                {{ $notification->data['message'] }}
            </span>
            <span class="text-xs text-gray-500">
                {{ $notification->created_at->diffForHumans() }}
            </span>
        </a>
        @else
        <a href="{{ $notification->data['link'] }}"
            class="border-b border-gray-300 p-2 bg-gray-200 hover:bg-gray-300 flex justify-between"
            @click="$dispatch('mark-as-read' , {id: '{{ $notification->id }}' })">
            <span>
                {{ $notification->data['message'] }}
            </span>
            <!-- Time -->
            <span class="text-xs text-gray-500">
                {{ $notification->created_at->diffForHumans() }}
            </span>
        </a>
        @endif
        </a>
    </li>
    @endforeach
</ul>
@endif

@endsection