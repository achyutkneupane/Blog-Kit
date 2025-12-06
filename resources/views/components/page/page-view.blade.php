<x-layouts.app>
    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 antialiased">
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
</x-layouts.app>
