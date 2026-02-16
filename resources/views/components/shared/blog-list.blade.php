<div class="container-xl relative my-16">
    @if($title)
        <div class="mb-10">
            <x-shared.solid-section :text="$title" />
        </div>
    @endif

    <div @class([
        "grid gap-8 transition-all duration-500",
        "grid-cols-1 md:grid-cols-2 lg:grid-cols-3" => $blogs->count() > 0,
        "grid-cols-1" => $blogs->count() === 0
    ])>
        @forelse($blogs as $blog)
            <div class="group transform transition-all duration-300 hover:-translate-y-2">
                <x-shared.blog-component :blog="$blog" />
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 px-6 bg-neutral-50/50 border-2 border-dashed border-neutral-200 rounded-[2.5rem]">
                <div class="p-4 bg-white rounded-2xl shadow-xs mb-4">
                    <svg class="w-10 h-10 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 3v5h5"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-neutral-900">No stories found</h3>
                <p class="text-neutral-500 mt-2 text-center max-w-xs">
                    We haven't published anything here yet. Check back soon for fresh insights!
                </p>
            </div>
        @endforelse
    </div>
</div>
