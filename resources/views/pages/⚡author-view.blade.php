<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Component;

new class extends Component
{
    public User $user;
};
?>

<main class="container-xl relative my-16 antialiased">
    <div class="bg-white/70 backdrop-blur-md border border-neutral-200/60 rounded-[2.5rem] shadow-sm overflow-hidden">
        <div class="px-6 py-12 lg:py-16 border-b border-neutral-100/80">
            <header class="max-w-3xl mx-auto text-center">
                <img
                    src="{{ $user->avatar }}"
                    alt="{{ $user->name }}"
                    width="112"
                    height="112"
                    decoding="async"
                    class="mx-auto h-28 w-28 rounded-full object-cover ring-4 ring-primary/10 shadow-sm"
                >

                <h1 class="mt-6 text-4xl lg:text-5xl font-black tracking-tight text-neutral-900 leading-tight">
                    {{ $user->name }}
                </h1>

                @if (filled($user->job_title))
                    <p class="mt-3 text-xs font-bold uppercase tracking-widest text-primary">
                        {{ $user->job_title }}
                    </p>
                @endif

                @if (filled($user->bio))
                    <p class="mt-6 text-neutral-500 leading-relaxed">
                        {{ $user->bio }}
                    </p>
                @endif

                @if (filled($user->website))
                    <a
                        href="{{ $user->website }}"
                        rel="me noopener"
                        target="_blank"
                        class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline"
                    >
                        {{ parse_url($user->website, PHP_URL_HOST) ?: $user->website }}
                    </a>
                @endif
            </header>
        </div>

        <div class="px-6 py-12 lg:px-16 lg:py-20">
            <x-shared.blog-list
                :title="'Articles by '.$user->name"
                :blogs="$user->blogs()->latest('published_at')->get()"
            />
        </div>
    </div>
</main>

@push('seo')
    {!! seo()->for($user) !!}
@endpush
