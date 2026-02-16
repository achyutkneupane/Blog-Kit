<footer class="container-xl mt-16 mb-8">
    <div class="bg-white/70 backdrop-blur-md border border-neutral-200/60 rounded-[2.5rem] shadow-sm px-8 py-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

            <div class="max-w-xs">
                <a href="{{ route('landing-page') }}" class="group flex items-center space-x-2">
                    <span class="text-2xl font-black tracking-tight text-neutral-900 group-hover:text-primary transition-colors">
                        {{ $settings->name }}
                    </span>
                </a>
                <p class="mt-4 text-sm text-neutral-500 leading-relaxed">
                    A modern blogging foundation built with the TALL stack, designed for speed and SEO.
                </p>
            </div>

            <nav>
                <ul class="flex flex-wrap gap-x-8 gap-y-4 text-sm font-bold text-neutral-600">
                    <li>
                        <a href="{{ route('landing-page') }}"
                           class="transition-colors {{ request()->routeIs('landing-page') ? 'text-primary' : 'hover:text-primary' }}">
                            Home
                        </a>
                    </li>
                    @foreach(\App\Models\StaticPage::query()->withGlobalScope('contentPage', new \App\Models\Scopes\ContentPageOnly())->get() as $staticPage)
                        <li>
                            <a href="{{ route('page.view', $staticPage) }}"
                               class="transition-colors {{ \Illuminate\Support\Facades\Route::named('page.view', $staticPage) ? 'text-primary' : 'hover:text-primary' }}">
                                {{ $staticPage->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="my-8 h-px w-full bg-linear-to-r from-transparent via-neutral-200 to-transparent"></div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-sm text-neutral-500 font-medium">
                © {{ now()->format('Y') }}
                <a href="https://github.com/achyutkneupane/Blog-Kit" target="_blank" class="text-neutral-900 hover:text-primary underline decoration-primary/30 underline-offset-4">
                    {{ $settings->name }}
                </a>.
                All Rights Reserved.
            </div>

            <div class="flex items-center gap-3">
                <span class="text-[10px] uppercase tracking-widest font-bold text-neutral-400">Built with</span>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-1 rounded-md bg-primary/10 text-primary text-xs font-bold border border-primary/20">Laravel {{ \Composer\InstalledVersions::getPrettyVersion('laravel/framework') }}</span>
                    <span class="px-2 py-1 rounded-md bg-neutral-100 text-neutral-600 text-xs font-bold border border-neutral-200">Filament {{ \Composer\InstalledVersions::getPrettyVersion('filament/filament') }}</span>
                </div>
            </div>
        </div>
    </div>
</footer>
