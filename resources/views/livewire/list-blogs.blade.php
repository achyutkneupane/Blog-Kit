<main class="container-xl relative my-12 antialiased">
    <div class="flex flex-col lg:flex-row gap-8">

        <aside class="w-full lg:w-72 shrink-0">
            <div class="relative group mb-3">
                <div
                    class="absolute -inset-1 bg-linear-to-r from-primary/10 to-transparent rounded-4xl blur-md opacity-0 group-focus-within:opacity-100 transition duration-500"></div>
                <div
                    class="relative flex items-center bg-white/70 backdrop-blur-md border border-neutral-200/60 rounded-2xl px-3 py-2 shadow-xs group-focus-within:border-primary/40 transition-all">
                    <svg class="w-5 h-5 text-neutral-400 group-focus-within:text-primary transition-colors" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search articles"
                        class="w-full bg-transparent border-none focus:ring-0 text-neutral-900 placeholder:text-neutral-400 font-medium px-2 py-2 focus:outline-none"
                    >
                    <div wire:loading wire:target="search, selectedCategories">
                        <svg class="animate-spin h-5 w-5 text-primary" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                    fill="none"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/70 backdrop-blur-md border border-neutral-200/60 rounded-2xl p-6 shadow-xs">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-neutral-900 uppercase tracking-tight">Filters</h3>
                    <button wire:click="resetFilters"
                            class="text-xs font-bold text-primary hover:text-primary-600 transition-colors">
                        Clear All
                    </button>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-neutral-400 uppercase tracking-widest">Categories</h4>
                    <div class="flex flex-col gap-3">
                        @foreach($categories as $category)
                            <label class="flex items-center group cursor-pointer">
                                <div class="relative flex items-center">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selectedCategories"
                                        value="{{ $category->id }}"
                                        class="peer appearance-none w-5 h-5 border-2 border-neutral-200 rounded-lg checked:bg-primary checked:border-primary transition-all duration-200"
                                    >
                                    <svg
                                        class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 ml-1 pointer-events-none transition-opacity"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                              d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span
                                    class="ml-3 text-sm font-semibold text-neutral-600 group-hover:text-neutral-900 peer-checked:text-primary transition-colors">
                                        {{ $category->name }}
                                    </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-neutral-100">
                    <div class="p-4 bg-primary/5 rounded-2xl border border-primary/10">
                        <p class="text-xs font-medium text-neutral-500 leading-relaxed">
                            Showing <span class="text-primary font-bold">{{ $blogs->count() }}</span> blogs tailored for you.
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($blogs as $blog)
                    <x-shared.blog-component :blog="$blog"/>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="bg-neutral-50 rounded-[2.5rem] py-12 border-2 border-dashed border-neutral-200">
                            <p class="text-neutral-500 font-medium">No articles match your criteria.</p>
                            <button wire:click="resetFilters" class="mt-4 text-primary font-bold hover:underline">Try
                                broadening your search
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

@push('seo')
    {!! seo()->for($staticPage) !!}
@endpush
