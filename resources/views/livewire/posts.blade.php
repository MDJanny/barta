<div>
    @if($posts->count() > 0)
    <div class="space-y-6">
        @foreach($posts as $post)
        <x-post-card :post="$post" />
        @endforeach

        @if($posts->count() >= $amount)
        <div class="flex justify-center">
            <button wire:click="loadMore" wire:loading.attr="disabled" wire:loading.class="hidden" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:enabled:bg-gray-700">Load
                More</button>
            <span wire:loading wire:target="loadMore" class="text-gray-500">Loading...</span>
        </div>
        @else
        <p class=" text-center mt-14 text-gray-600">Reached the end!</p>
        @endif
    </div>
    @else
    <p class="text-center mt-14 text-gray-500">No posts yet!</p>
    @endif
</div>