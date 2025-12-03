<nav class="bg-neutral-900 sticky w-full z-20 top-0 start-0 min-h-[72px]">
    <div class="container-xl flex flex-wrap items-center justify-between py-4">
        <a href="{{ route('landing-page') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-3xl text-neutral-50 font-semibold whitespace-nowrap">
                {{ $settings->name }}
            </span>
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <a class="text-white bg-primary/90 hover:bg-primary box-border border border-transparent shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2 focus:outline-none" href="https://github.com/achyutkneupane/Blog-Kit" target="_blank">
                Get started
            </a>
            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white hover:text-neutral-300 rounded-base md:hidden hover:bg-primary/5 focus:outline-none" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"></path></svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium rounded-base bg-neutral-700 md:bg-transparent space-y-1 md:space-y-0 space-x-0 md:space-x-8 md:flex-row md:mt-0 md:border-0">
                <li>
                    <a href="#" class="block py-2 px-3 text-white bg-primary rounded-sm md:bg-transparent md:text-primary md:p-0" aria-current="page">Home</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-3 text-white rounded hover:bg-primary/20 md:hover:bg-transparent md:border-0 md:hover:text-primary/80 md:p-0">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
