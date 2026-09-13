@props(['faqs'])

<section class="container-xl relative my-16">
    <x-shared.solid-section text="Frequently Asked Questions" />

    <div class="mx-auto mt-8 max-w-3xl space-y-4" x-data="{ open: 0 }">
        @foreach ($faqs as $index => $faq)
            <div class="overflow-hidden rounded-2xl border border-neutral-200/70 bg-white shadow-xs">
                <button
                    type="button"
                    x-on:click="open = open === {{ $index }} ? null : {{ $index }}"
                    class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
                >
                    <span class="text-lg font-bold text-neutral-900">{{ $faq->question }}</span>
                    <svg
                        class="h-5 w-5 shrink-0 text-primary transition-transform duration-200"
                        x-bind:class="open === {{ $index }} ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open === {{ $index }}" x-transition class="px-6 pb-6 leading-relaxed text-neutral-500">
                    {!! $faq->answer !!}
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 text-center">
        <a
            href="{{ route('faq.view') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline"
            wire:navigate.hover
        >
            View all FAQs
        </a>
    </div>
</section>
