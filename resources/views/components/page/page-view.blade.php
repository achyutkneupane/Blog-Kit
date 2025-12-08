<x-layouts.app>
    <main class="container-xl bg-white w-full z-20 top-0 start-0 min-h-[72px] my-6 py-8 lg:py-16 px-4 antialiased rounded-base border-2 border-neutral-200 shadow-sm shadow-neutral-200">
        <div class="flex justify-between px-4 container-xl">
            <article class="mx-auto w-full format format-sm sm:format-base max-w-full">
                <header class="mb-4 lg:mb-6 not-format">
                    <h1 class="mb-2 text-3xl font-extrabold leading-tight text-primary lg:mb-6 lg:text-4xl text-center">
                        {{ $staticPage->title }}
                    </h1>
                </header>
                {!! $staticPage->content !!}
            </article>
        </div>
    </main>
    @push('seo')
        {!! seo()->for($staticPage) !!}
    @endpush
</x-layouts.app>
