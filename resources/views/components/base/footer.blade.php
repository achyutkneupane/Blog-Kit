<footer class="bg-neutral-900">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="{{ route('landing-page') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-3xl text-neutral-50 font-semibold whitespace-nowrap">
                {{ $settings->name }}
            </span>
            </a>
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-neutral-50 sm:mb-0">
                <li>
                    <a href="{{ route('landing-page') }}" class="hover:underline text-primary me-4 md:me-6">Homepage</a>
                </li>
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">About</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-default sm:mx-auto lg:my-8">
        <span class="block text-sm text-neutral-300 sm:text-center">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.</span>
    </div>
</footer>
