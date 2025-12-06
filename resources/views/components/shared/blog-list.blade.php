<div class="flex flex-col gap-8 my-12">
    @if($title)
        <x-shared.solid-section :text="$title" />
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($blogs as $blog)
            <x-shared.blog-component :blog="$blog" />
        @empty
            <p class="text-center text-neutral-500 col-span-full">No blogs available.</p>
        @endforelse
    </div>
</div>
