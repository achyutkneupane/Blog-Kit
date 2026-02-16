<article class="group relative flex flex-col h-full bg-white border border-neutral-200/70 rounded-3xl p-6 shadow-xs hover:shadow-xl hover:shadow-neutral-200/40 transition-all duration-300">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div class="flex flex-wrap gap-2">
            @foreach($blog->categories as $category)
                <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-primary bg-primary/10 rounded-full border border-primary/5">
                    {{ $category->name }}
                </span>
            @endforeach
        </div>

        <div class="flex items-center text-xs font-semibold text-neutral-400 bg-neutral-50 px-2 py-1 rounded-lg">
            <svg class="w-3.5 h-3.5 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $blog->minutes_read_text }}
        </div>
    </div>

    <h2 class="mb-3 text-2xl font-black tracking-tight text-neutral-900 group-hover:text-primary transition-colors duration-200">
        <a href="{{ route('blog.view', $blog) }}" class="after:absolute after:inset-0">
            {{ $blog->title }}
        </a>
    </h2>

    <p class="mb-6 text-neutral-500 line-clamp-3 text-sm leading-relaxed font-medium">
        {{ $blog->description }}
    </p>

    <div class="mt-auto pt-6 border-t border-neutral-100 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-linear-to-br from-neutral-200 to-neutral-100 flex items-center justify-center text-[10px] font-bold text-neutral-500 border border-neutral-200">
                {{ substr($blog->author->name, 0, 1) }}
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-neutral-900 leading-none mb-1">
                    {{ $blog->author->name }}
                </span>
                <span class="text-[11px] font-medium text-neutral-400">
                    {{ $blog->published_at->format('M d, Y') }}
                </span>
            </div>
        </div>

        <div class="relative z-10">
             <span class="text-primary group-hover:translate-x-1 inline-flex items-center transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </span>
        </div>
    </div>
</article>
