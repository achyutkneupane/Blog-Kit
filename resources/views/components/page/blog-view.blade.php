<x-layouts.app>
    <main class="container-xl bg-white w-full z-20 top-0 start-0 min-h-[72px] my-6 py-8 lg:py-16 px-4 antialiased rounded-base border-2 border-neutral-200 shadow-sm shadow-neutral-200">
        <div class="flex justify-between px-4 container-xl">
            <article class="mx-auto w-full format format-sm sm:format-base max-w-full">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-neutral-900">
                            <img class="mr-4 w-16 h-16 rounded-full" src="{{ $blog->author->avatar }}" alt="{{ $blog->author->name }}">
                            <div>
                                <a href="#" rel="author" class="text-xl font-bold text-primary">
                                    {{ $blog->author->name }}
                                </a>
                                <p class="text-base text-neutral-500">
                                    <time pubdate="" datetime="{{ $blog->published_at->toDateString() }}" title="{{ $blog->published_at->toFormattedDateString() }}">
                                        {{ $blog->published_at->toFormattedDateString() }}
                                    </time>
                                </p>
                            </div>
                        </div>
                    </address>
                    <h1 class="mb-2 text-3xl font-extrabold leading-tight text-primary lg:mb-6 lg:text-4xl">
                        {{ $blog->title }}
                    </h1>
                    <div class="flex flex-row gap-2">
                        @foreach($blog->categories as $category)
                            <a href="#" class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded">
                                {{ $category->name }}
                            </a>
                        @endforeach
                        <div>·</div>
                        <div>{{ $blog->minutes_read_text }}</div>
                    </div>
                </header>
                {!! $blog->content !!}
            </article>
        </div>
    </main>
    @push('seo')
        {!! seo()->for($blog) !!}
    @endpush
</x-layouts.app>
