<x-layouts.app>
    <main class="container-xl relative my-16 antialiased">
        <div class="bg-white/70 backdrop-blur-md border border-neutral-200/60 rounded-[2.5rem] shadow-sm overflow-hidden">
            <div class="px-6 py-10 lg:px-8 lg:py-16 border-b border-neutral-100/80">
                <header class="max-w-6xl mx-auto">

                    <div class="flex items-center gap-4 mb-8">
                        <img class="w-14 h-14 rounded-2xl object-cover ring-4 ring-primary/5 shadow-sm" src="{{ $blog->author->avatar }}" alt="{{ $blog->author->name }}">
                        <div class="flex flex-col">
                        <span class="text-lg font-black text-neutral-900 leading-tight">
                            {{ $blog->author->name }}
                        </span>
                            <div class="flex items-center gap-2 text-sm font-medium text-neutral-400">
                                <time datetime="{{ $blog->published_at->toDateString() }}">
                                    {{ $blog->published_at->toFormattedDateString() }}
                                </time>
                                <span>•</span>
                                <span class="text-primary/80">{{ $blog->minutes_read_text }}</span>
                            </div>
                        </div>
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-black tracking-tight text-neutral-900 leading-[1.15] mb-6">
                        {{ $blog->title }}
                    </h1>

                    <div class="flex flex-wrap gap-2">
                        @foreach($blog->categories as $category)
                            <a href="#" class="px-3 py-1 text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 rounded-lg transition-colors hover:bg-primary/20">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </header>
            </div>

            <div class="px-6 py-12 lg:px-16 lg:py-20">
                <article class="mx-auto w-full max-w-6xl prose prose-neutral prose-lg lg:prose-xl
                prose-headings:font-black prose-headings:tracking-tight prose-headings:text-neutral-900
                prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                prose-img:rounded-3xl prose-img:shadow-lg
                prose-blockquote:border-primary prose-blockquote:bg-primary/5 prose-blockquote:py-2 prose-blockquote:px-6 prose-blockquote:rounded-r-2xl">

                    {!! $blog->content !!}

                </article>
            </div>
        </div>
    </main>
    @push('seo')
        {!! seo()->for($blog) !!}
    @endpush
</x-layouts.app>
