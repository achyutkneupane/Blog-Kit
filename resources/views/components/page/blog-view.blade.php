<x-layouts.app>
    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 antialiased">
        <div class="flex justify-between px-4 container-xl">
            <article class="mx-auto w-full format format-sm sm:format-base max-w-full">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-neutral-900">
                            <img class="mr-4 w-16 h-16 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="Jese Leos">
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
                    <div>
                        @foreach($blog->categories as $category)
                            <a href="#" class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2">
                                {{ $category->name }}
                            </a>
                        @endforeach
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
