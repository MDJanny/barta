@extends('layouts.app')

@section('content')
<div>
    <h1 class="text-xl font-semibold leading-7 text-gray-900">Search results for "{{ request('query') }}"</h1>
    <p class="mt-1 text-gray-500">{{ $users->count() }} users found!</p>
</div>

@if ($users->count() > 0)
<div class="divide-y divide-gray-200">
    @foreach ($users as $user)
    <a href="{{ route('profile.index', $user->username) }}"
        class="flex items-center bg-gray-200/40 hover:bg-gray-200 p-4 rounded-lg">
        <img src="{{ $user->getAvatarUrl(true) }}" alt="{{ $user->name }}'s avatar" width="60" class="mr-4 rounded">

        <div>
            <h4 class="font-bold">
                {{ $user->name }}
            </h4>

            <h5 class="text-sm">
                {{ '@' . $user->username }}
            </h5>

            <h5 class="text-sm">
                {{ $user->email }}
            </h5>
        </div>
    </a>
    @endforeach
</div>
@endif
@endsection