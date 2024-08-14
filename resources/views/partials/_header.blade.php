<header class="dark:border-neutral-700 dark:bg-neutral-900 bg-neutral-50 border-b border-neutral-300 w-full">
    <nav x-data="{ mobileMenuIsOpen: false, userDropDownIsOpen: false, openWithKeyboard: false }" @click.away="mobileMenuIsOpen = false"
         class="flex items-center mx-auto justify-between px-4 py-2 container" aria-label="menu">
        <!-- Brand Logo -->
        <a href="#" class="text-2xl font-bold text-neutral-900 dark:text-white">
            <x-application-logo class="w-10 h-10 fill-current text-gray-500"/>
        </a>

        <!-- Desktop Menu -->
        <ul class="hidden items-center gap-4 sm:flex">
            <li>
                <a href="#"
                   class="font-bold text-black underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-white dark:hover:text-white"
                   aria-current="page">{{ __('movies') }}</a>
            </li>
            <li>
                <a href="#"
                   class="font-medium text-neutral-600 underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-neutral-300 dark:hover:text-white">
                    {{ __('tv') }}
                </a>
            </li>
            <li>
                <a href="#"
                   class="font-medium text-neutral-600 underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-neutral-300 dark:hover:text-white">
                    {{ __('more') }}
                </a>
            </li>
        </ul>

        <!-- Right Side -->
        <ul class="hidden items-center gap-4 sm:flex">
            @auth
                <!-- User Pic -->
                <li x-data="{ userDropDownIsOpen: false, openWithKeyboard: false }"
                    @keydown.esc.window="userDropDownIsOpen = false; openWithKeyboard = false"
                    class="relative flex items-center">
                    <button @click="userDropDownIsOpen = !userDropDownIsOpen" :aria-expanded="userDropDownIsOpen"
                            @keydown.space.prevent="openWithKeyboard = true"
                            @keydown.enter.prevent="openWithKeyboard = true" @keydown.down.prevent="openWithKeyboard = true"
                            class="rounded-full focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:focus-visible:outline-white"
                            aria-controls="userMenu">
                                <span
                                    class="flex size-10 items-center justify-center overflow-hidden rounded-full border border-neutral-300 bg-neutral-50 text-neutral-600/50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300/50">
                                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"
                                          class="w-full h-full mt-3">
                            <path fill-rule="evenodd"
                                  d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                                  clip-rule="evenodd"/>
                                  </svg>
</span>
                    </button>
                    <!-- User Dropdown -->
                    <ul x-cloak x-show="userDropDownIsOpen || openWithKeyboard" x-transition.opacity
                        x-trap="openWithKeyboard" @click.outside="userDropDownIsOpen = false; openWithKeyboard = false"
                        @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                        id="userMenu"
                        class="absolute right-0 top-12 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-md border border-neutral-300 bg-neutral-50 py-1.5 dark:border-neutral-700 dark:bg-neutral-900">
                        <li class="border-b border-neutral-300 dark:border-neutral-700">
                            <div class="flex flex-col px-4 py-2">
                                <span class="text-sm font-medium text-neutral-900 dark:text-white"> {{ auth()->user()->name }}</span>
                                <p class="text-xs text-neutral-600 dark:text-neutral-300">{{ auth()->user()->email }}</p>
                            </div>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('dashboard') }}"
                               class="block bg-neutral-50 px-4 py-2 text-sm text-neutral-600 hover:bg-neutral-900/5 hover:text-neutral-900 focus-visible:bg-neutral-900/10 focus-visible:text-neutral-900 focus-visible:outline-none dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-50/5 dark:hover:text-white dark:focus-visible:bg-neutral-50/10 dark:focus-visible:text-white">{{ __('dashboard') }}</a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('profile.edit') }}"
                               class="block bg-neutral-50 px-4 py-2 text-sm text-neutral-600 hover:bg-neutral-900/5 hover:text-neutral-900 focus-visible:bg-neutral-900/10 focus-visible:text-neutral-900 focus-visible:outline-none dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-50/5 dark:hover:text-white dark:focus-visible:bg-neutral-50/10 dark:focus-visible:text-white">{{ __('profile') }}</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   class="block bg-neutral-50 px-4 py-2 text-sm text-neutral-600 hover:bg-neutral-900/5 hover:text-neutral-900 focus-visible:bg-neutral-900/10 focus-visible:text-neutral-900 focus-visible:outline-none dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-50/5 dark:hover:text-white dark:focus-visible:bg-neutral-50/10 dark:focus-visible:text-white">{{ __('Logout') }}</a>
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth

            @guest
                <li>
                    <a wire:navigate href="{{ route('login') }}"
                       class="block bg-neutral-50 px-4 py-2 text-sm text-neutral-600 hover:bg-neutral-900/5 hover:text-neutral-900 focus-visible:bg-neutral-900/10 focus-visible:text-neutral-900 focus-visible:outline-none dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-50/5 dark:hover:text-white dark:focus-visible:bg-neutral-50/10 dark:focus-visible:text-white">{{ __('Login') }}</a>
                </li>
                <li>
                    <a wire:navigate href="{{ route('register') }}"
                       class="block bg-neutral-50 px-4 py-2 text-sm text-neutral-600 hover:bg-neutral-900/5 hover:text-neutral-900 focus-visible:bg-neutral-900/10 focus-visible:text-neutral-900 focus-visible:outline-none dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-50/5 dark:hover:text-white dark:focus-visible:bg-neutral-50/10 dark:focus-visible:text-white">{{ __('Register') }}</a>
                </li>
            @endguest
        </ul>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenuIsOpen = !mobileMenuIsOpen" :aria-expanded="mobileMenuIsOpen"
                :class="mobileMenuIsOpen ? 'fixed top-6 right-6 z-20' : null" type="button"
                class="flex text-neutral-600 dark:text-neutral-300 sm:hidden" aria-label="mobile menu"
                aria-controls="mobileMenu">
            <svg x-cloak x-show="!mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
            <svg x-cloak x-show="mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Mobile Menu -->
        <ul x-cloak x-show="mobileMenuIsOpen"
            x-transition:enter="transition motion-reduce:transition-none ease-out duration-300"
            x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition motion-reduce:transition-none ease-out duration-300"
            x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full"
            class="fixed max-h-svh overflow-y-auto inset-x-0 top-0 z-10 flex flex-col rounded-b-md border-b border-neutral-300 bg-neutral-50 px-8 pb-6 pt-10 dark:border-neutral-700 dark:bg-neutral-900 sm:hidden">

            @auth
                <li class="mb-4 border-none">
                    <div class="flex items-center gap-2 py-2">
                        <span class="flex size-12 items-center justify-center overflow-hidden rounded-full border border-neutral-300 bg-neutral-50 text-neutral-600/50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300/50">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"  class="w-full h-full mt-3">
                             <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"/>
                        </svg>
                        </span>
                        <div>
                            <span class="font-medium text-neutral-900 dark:text-white">{{ auth()->user()->name }}</span>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </li>
                <li class="p-2">
                    <a  href="#" class="w-full text-lg font-bold text-black focus:underline dark:text-white"
                       aria-current="page">{{ __('movies') }}</a>
                </li>
                <li class="p-2">
                    <a href="#"
                       class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">{{ __('tv') }}</a>
                </li>
                <li class="p-2">
                    <a href="#"
                       class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">{{ __('more') }}</a>
                </li>
                <hr role="none" class="my-2 border-outline dark:border-neutral-700">
                <li class="p-2">
                    <a wire:navigate href="{{ route('dashboard') }}"
                       class="w-full text-neutral-600 focus:underline dark:text-neutral-300">{{ __('dashboard') }}</a>
                </li>
                <li class="p-2">
                    <a  wire:navigate href="{{ route('profile.edit') }}" class="w-full text-neutral-600 focus:underline dark:text-neutral-300">{{ __('profile') }}</a>
                </li>
                <li class="mt-4 w-full border-none">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           class="rounded-md bg-black px-4 py-2 block text-center font-medium tracking-wide text-neutral-100 hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 dark:bg-white dark:text-black dark:focus-visible:outline-white">{{ __('Logout') }}</a>
                    </form>
                </li>
            @endauth

            @guest
                <li class="p-2">
                    <a wire:navigate href="{{ route('login') }}"
                       class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">{{ __('Login') }}</a>
                </li>
                <li wire:navigate class="p-2">
                    <a href="{{ route('register') }}"
                       class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">{{ __('Register') }}</a>
                </li>
            @endguest
        </ul>
    </nav>
</header>
