@extends('layouts.app')

@section('title', 'Post')
@section('content')
<main class="container max-w-xl mx-auto space-y-8 mt-8 px-2 md:px-0 min-h-screen">

    <!-- Single post -->
    <section id="newsfeed" class="space-y-6">
        <x-post-card :post="$post" />
    </section>
    <!-- /Single post -->
</main>
@endsection