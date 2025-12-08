<footer class="container-xl bg-white/60 sticky w-full z-20 top-0 start-0 min-h-[72px] mb-4 px-4 rounded-base border-2 border-neutral-200 backdrop-blur-sm shadow-sm shadow-neutral-200">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="{{ route('landing-page') }}" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <span class="text-heading self-center text-2xl font-semibold whitespace-nowrap">
                    {{ $settings->name }}
                </span>
            </a>

            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-body sm:mb-0">
                <li>
                    <a
                        href="{{ route('landing-page') }}"
                        @class([
                            "hover:underline me-4 md:me-6",
                            request()->routeIs('landing-page') ? 'text-primary' : 'hover:text-primary'
                        ])
                    >
                        Home
                    </a>
                </li>

                @foreach(
                    \App\Models\StaticPage::query()
                        ->withGlobalScope('contentPage', new \App\Models\Scopes\ContentPageOnly())
                        ->get() as $staticPage
                )
                    <li>
                        <a
                            href="{{ route('page.view', $staticPage) }}"
                            @class([
                                "hover:underline me-4 md:me-6",
                                \Illuminate\Support\Facades\Route::named('page.view', $staticPage) ? 'text-primary' : 'hover:text-primary'
                            ])
                        >
                            {{ $staticPage->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <hr class="my-6 border-default sm:mx-auto lg:my-8">

        <span class="block text-sm text-body sm:text-center">
            © {{ now()->format('Y') }}
            <a href="https://github.com/achyutkneupane/Blog-Kit" target="_blank" class="hover:underline">
                {{ $settings->name }}
            </a>.
            All Rights Reserved.
        </span>
    </div>
</footer>
