<div class="bg-gradient-to-b border-b from-[#f8fcfdab] isolate py-2 text-center z-10 border-gray-50 text-slate-700 dark:border-slate-800 dark:from-slate-900/50 dark:text-slate-300 text-sm shadow-sm">
    <p class="text-sm">🎉 {{ __('Nylo v7 is here!') }} <a href="{{ route('learn-more.v7') }}" class="underline hover:text-gray-900 transition-all font-medium">{{ __('See what\'s new') }} →</a></p>
</div>


<header class="backdrop-blur bg-white/90 dark:bg-slate-900/90 dark:border-slate-50/[0.06] duration-500 flex-none lg:border-slate-900/10 lg:z-50 sticky supports-backdrop-blur:bg-white/60 dark:supports-backdrop-blur:bg-slate-900/60 top-0 transition-colors w-full z-40" style="position: relative; max-height: 83px;">
    <div class="max-w-8xl mx-auto">
        <div class="border-b border-slate-900/10 dark:border-slate-300/10 lg:border-0 lg:mx-0 lg:px-4 py-4">
            <div class="relative flex items-center justify-between mx-5 md:mx-5 md:justify-start">
                <a class="flex-none overflow-hidden md:w-auto" href="{{ route('landing.home') }}">
                    <img src="{{ asset('images/nylo_logo_filled.png') }}" class="h-[50px] dark:brightness-0 dark:invert" alt="{{ config('app.name') }} logo" />
                </a>

                <div class="relative hidden sm:flex items-center ml-auto">
                    <nav class="leading-6 text-slate-700 dark:text-slate-200">
                        <ul class="flex space-x-8">
                            <li>
                                <a class="flex hover:text-primary-blue transition-all text-gray-600 dark:text-slate-300 dark:hover:text-primary-blue" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}">{{ __('Docs') }}</a>
                            </li>
                            {{-- Framework Dropdown --}}
                            <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="flex items-center hover:text-primary-blue transition-all text-gray-600 dark:text-slate-300 dark:hover:text-primary-blue">
                                    {{ __('Framework') }}
                                    <svg class="ml-1 w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Panel --}}
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute top-full left-1/2 -translate-x-1/2 mt-3 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 z-50 w-[700px]"
                                     style="display: none;">

                                    {{-- Arrow pointer --}}
                                    <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white dark:bg-slate-800 border-l border-t border-slate-200 dark:border-slate-700 rotate-45"></div>

                                    {{-- Flutter Packages Section --}}
                                    <div class="relative">

                                        <div class="grid grid-cols-3 gap-3">

                                            <div class="col-span-3">
                                                <span class="mb-2 block font-medium border-b border-dashed border-slate-100 dark:border-slate-700 pb-2">
                                                    {{ __('Nylo Framework') }}</span>
                                            </div>

                                            <div class="col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                   
                                                <a href="https://github.com/nylo-core/nylo/tree/7.x" target="_BLANK" class="block w-full">
                                                <div class="items-center gap-4 border border-gray-100 dark:border-slate-700 rounded-lg transition-colors
                                                hover:bg-gradient-to-b
                                                hover:from-slate-50 hover:to-white
                                                dark:hover:from-slate-700 dark:hover:to-slate-800
                                                ">
                                                <div class="section-divider"></div>

                                                    <div class="flex flex-col p-3">
                                                        <span class="font-semibold dark:text-slate-100">
                                                            {{ __('Nylo v7 Framework') }}
                                                        </span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm">
                                                            {{ __('The boilerplate to kickstart your Flutter app with Nylo.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                </a>
                                                
                                                <button type="button" onclick="copyCommand(this)" class="block w-full group bg-gradient-to-r from-[#f8fcfdab] p-3 rounded-lg dark:border-slate-700 hover:from-white hover:to-white dark:from-slate-800/50 dark:hover:from-slate-700/50 transition-colors"
                                                 data-command="dart pub global activate nylo">
                                                    <div class="flex flex-col items-start rounded-lg transition-colors">

                                                        <div class="text-sm mb-1 text-slate-500">{{ __('Get started: Nylo installer') }}</div>
                                                      <div class="bg-gradient-to-r border-l border-neutral-300 border-r flex flex-col from-gray-50 items-center rounded-lg transition-colors dark:from-slate-700 dark:border-slate-600">
                                                            <div class="dark:bg-slate-700/50 flex font-medium gap-2 hover:bg-gray-50 items-center px-3 py-2 rounded-lg">
                                                                <div>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#54a9d6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19h8M4 17l6-6l-6-6"></path></svg>
                                                                </div>
                                                                <div class="text-sm">dart pub global activate nylo_installer</div>
                                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-600">
                                                                    <svg class="copy-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.2474 6.25033V2.91699H17.0807V13.7503H13.7474M13.7474 6.25033V17.0837H2.91406V6.25033H13.7474Z" stroke="currentColor" stroke-linecap="round"></path>
                                                                    </svg>
                                                                    <svg class="check-icon hidden" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="copy-instructions hidden text-xs text-left mt-3">
                                                            <span>Run: </span><span class="font-medium text-gray-500">dart pub global activate nylo_installer</span><span> in your terminal.</span>
                                                            <div class="block">
                                                                <span class="text-xs">Next: Run </span><span class="font-medium text-blue-500">nylo new example_project</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </button>
           
                                            </div>

                                            <div class="col-span-3 mt-2">
                                                <span class="block border-b border-dashed border-slate-100 dark:border-slate-700 font-medium pb-2">
                                                    {{ __('Packages') }}</span>
                                            </div>
                                            <div class="col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">

                                                @foreach(config('project.packages-index') as $package)
                                                <a href="https://pub.dev/packages/{{ str($package['link'])->replace('-', '_') }}" target="_BLANK" class="block w-full">
                                                <div class="items-center gap-4 border border-gray-100 dark:border-slate-700 rounded-lg transition-colors
                                                hover:bg-gradient-to-b
                                                hover:from-slate-50 hover:to-white
                                                dark:hover:from-slate-700 dark:hover:to-slate-800
                                                ">
                                                <div class="section-divider"></div>

                                                    <div class="flex flex-col p-3">
                                                        <span class="font-semibold dark:text-slate-100">{{ $package['label'] }}</span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm">
                                                            {{ $package['description'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                </a>
                                                @endforeach
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </li>
                            {{-- Resources Dropdown --}}
                            <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="flex items-center hover:text-primary-blue transition-all text-gray-600 dark:text-slate-300 dark:hover:text-primary-blue">
                                    {{ __('Resources') }}
                                    <svg class="ml-1 w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Panel --}}
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute top-full left-1/2 -translate-x-1/2 mt-3 w-[620px] bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 z-50"
                                     style="display: none;">

                                    {{-- Arrow pointer --}}
                                    <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white dark:bg-slate-800 border-l border-t border-slate-200 dark:border-slate-700 rotate-45"></div>

                                    {{-- Resources Section --}}
                                    <div class="relative">

                                        <div class="grid grid-cols-3 gap-3">

                                            <div class="col-span-3">
                                                <span class="mb-4 block font-medium border-b border-dashed border-slate-100 dark:border-slate-700 pb-2">
                                                    {{ __('Developers') }}</span>
                                            </div>

                                            <div class="col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                   
                                                <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}" target="_BLANK" class="block w-full">
                                                <div class="items-center gap-4 border border-gray-100 dark:border-slate-700 rounded-lg transition-colors
                                                hover:bg-gradient-to-b
                                                hover:from-slate-50 hover:to-white
                                                dark:hover:from-slate-700 dark:hover:to-slate-800
                                                ">
                                                <div class="section-divider"></div>

                                                    <div class="flex flex-col p-3">

                                                        <div class="mb-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v14m4-9h2m-2-4h2M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4a4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3a3 3 0 0 0-3-3zm3-6h2M6 8h2"/></svg>
                                                        </div>

                                                        <span class="font-semibold dark:text-slate-100">
                                                            {{ __('Documentation') }}
                                                        </span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm  mt-1">
                                                            {{ __('Comprehensive guides to help you get started with Nylo.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                </a>
                                                
                                                <a href="https://github.com/nylo-core/nylo/releases" target="_BLANK" class="block w-full">
                                                <div class="items-center gap-4 border border-gray-100 dark:border-slate-700 rounded-lg transition-colors
                                                hover:bg-gradient-to-b
                                                hover:from-slate-50 hover:to-white
                                                dark:hover:from-slate-700 dark:hover:to-slate-800
                                                ">
                                                <div class="section-divider"></div>

                                                    <div class="flex flex-col p-3">

                                                        <div class="mb-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M15 18h-5m8-4h-8m-6 8h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="10" y="6" rx="1"/></g></svg>
                                                        </div>

                                                        <span class="font-semibold dark:text-slate-100">
                                                            {{ __('Release Notes') }}
                                                        </span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                                                            {{ __('Stay updated with the latest Nylo releases and updates.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                </a>
           
                                            </div>

                                            <div class="col-span-3 mt-4">
                                                <span class="block border-b border-dashed border-slate-100 dark:border-slate-700 font-medium pb-2">
                                                    {{ __('Resources') }}</span>
                                            </div>
                                            <div class="col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">

                                                @php
                                                    $resources = [
                                                        ['title' => __('YouTube'), 'url' => 'https://www.youtube.com/@nylo_dev', 'description' => __('Video tutorials and guides for Nylo framework.')],
                                                        ['title' => __('Forum'), 'url' => 'https://github.com/nylo-core/nylo/discussions', 'description' => __('Join the community discussions and get help.')],
                                                        ['title' => __('Become a sponsor'), 'url' => 'https://opencollective.com/nylo', 'description' => __('Support Nylo development by becoming a sponsor.')],
                                                    ];
                                                @endphp

                                                @foreach($resources as $resource)
                                                <a href="{{ $resource['url'] }}" target="_BLANK" class="block w-full">
                                                <div class="items-center gap-4 border border-gray-100 dark:border-slate-700 rounded-lg transition-colors
                                                hover:bg-gradient-to-b
                                                hover:from-slate-50 hover:to-white
                                                dark:hover:from-slate-700 dark:hover:to-slate-800
                                                ">
                                                <div class="section-divider"></div>

                                                    <div class="flex flex-col p-3">
                                                        <span class="font-semibold dark:text-slate-100">{{ $resource['title'] }}</span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm">
                                                            {{ $resource['description'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                </a>
                                                @endforeach
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <a class="flex hover:text-primary-blue transition-all text-gray-600 dark:text-slate-300 dark:hover:text-primary-blue" href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK">
                                    <span>{{ __('Packages') }}</span>

                                    <span class="ml-2 self-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="9" viewBox="0 0 13 13" fill="none">
<path d="M12.9199 0.62C12.8185 0.375651 12.6243 0.181475 12.3799 0.0799999C12.2597 0.028759 12.1306 0.00157999 11.9999 0H1.99994C1.73472 0 1.48037 0.105357 1.29283 0.292893C1.1053 0.48043 0.999939 0.734784 0.999939 1C0.999939 1.26522 1.1053 1.51957 1.29283 1.70711C1.48037 1.89464 1.73472 2 1.99994 2H9.58994L1.28994 10.29C1.19621 10.383 1.12182 10.4936 1.07105 10.6154C1.02028 10.7373 0.994141 10.868 0.994141 11C0.994141 11.132 1.02028 11.2627 1.07105 11.3846C1.12182 11.5064 1.19621 11.617 1.28994 11.71C1.3829 11.8037 1.4935 11.8781 1.61536 11.9289C1.73722 11.9797 1.86793 12.0058 1.99994 12.0058C2.13195 12.0058 2.26266 11.9797 2.38452 11.9289C2.50638 11.8781 2.61698 11.8037 2.70994 11.71L10.9999 3.41V11C10.9999 11.2652 11.1053 11.5196 11.2928 11.7071C11.4804 11.8946 11.7347 12 11.9999 12C12.2652 12 12.5195 11.8946 12.707 11.7071C12.8946 11.5196 12.9999 11.2652 12.9999 11V1C12.9984 0.869323 12.9712 0.740222 12.9199 0.62Z" fill="currentColor" class="text-gray-400 dark:text-slate-500"/>
</svg>
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </nav>

                    <div class="flex items-center ml-6 pl-6 border-l border-slate-200 dark:border-slate-700">

                        {{-- Search button --}}
                        <button @click="$store.search.toggle()" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center mr-1" aria-label="Search">
                            <span class="iconify lucide--search shrink-0 size-5 text-slate-600 dark:text-slate-400" aria-hidden="true"></span>
                        </button>

                        <a href="https://github.com/nylo-core/nylo" rel="noopener" target="_blank" aria-label="Nylo on GitHub" class="rounded-md font-medium inline-flex items-center transition-colors text-sm gap-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 p-1.5 mr-1">
                            <span class="iconify lucide--github shrink-0 size-5 text-slate-600 dark:text-slate-400" aria-hidden="true"></span>
                            <span class="truncate text-slate-600 dark:text-slate-400">{{ $githubStars }}</span>
                        </a>

                        {{-- Dark mode toggle --}}
                        <button @click="$store.darkMode.toggle()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors" aria-label="Toggle dark mode">
                            {{-- Sun icon (shown in dark mode) --}}
                            <svg x-show="$store.darkMode.on" x-cloak class="w-5 h-5 text-slate-400"
                            fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                            </svg>
                            {{-- Moon icon (shown in light mode) --}}
                            <svg x-show="!$store.darkMode.on" class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                        </button>
                        
                    </div>
                </div>

                {{-- Mobile Menu --}}
                <div class="flex items-center sm:hidden" x-data="{ mobileMenuOpen: false, frameworkOpen: false, resourcesOpen: false }">
                    {{-- Mobile search button --}}
                    <button @click="$store.search.toggle()" class="p-2 mr-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors" aria-label="Search">
                        <span class="iconify lucide--search shrink-0 size-5 text-slate-600 dark:text-slate-400" aria-hidden="true"></span>
                    </button>

                    {{-- Mobile dark mode toggle --}}
                    <button @click="$store.darkMode.toggle()" class="p-2 mr-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors" aria-label="Toggle dark mode">
                        <svg x-show="$store.darkMode.on" x-cloak class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                        <svg x-show="!$store.darkMode.on" class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                    </button>

                    {{-- Hamburger Menu Button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors" aria-label="Toggle menu">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    {{-- Mobile Menu Overlay --}}
                    <template x-teleport="body">
                        <div x-show="mobileMenuOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="mobileMenuOpen = false"
                             class="fixed inset-0 bg-black/50 z-50"
                             style="display: none;"></div>
                    </template>

                    {{-- Mobile Menu Panel --}}
                    <template x-teleport="body">
                        <div x-show="mobileMenuOpen"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full"
                             @keydown.escape.window="mobileMenuOpen = false"
                             class="fixed top-0 right-0 h-full w-80 max-w-[85vw] bg-white dark:bg-slate-900 shadow-xl z-[60] overflow-y-auto"
                             style="display: none;">

                        {{-- Mobile Menu Header --}}
                        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ __('Menu') }}</span>
                            <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Mobile Navigation --}}
                        <nav class="p-4">
                            <ul class="space-y-1">
                                {{-- Docs --}}
                                <li>
                                    <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}" class="flex items-center px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                        <svg class="w-5 h-5 mr-3 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        {{ __('Docs') }}
                                    </a>
                                </li>

                                {{-- Framework (Expandable) --}}
                                <li>
                                    <button @click="frameworkOpen = !frameworkOpen" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-3 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            {{ __('Framework') }}
                                        </span>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': frameworkOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    {{-- Framework Submenu --}}
                                    <div x-show="frameworkOpen" x-collapse class="ml-4 mt-1 space-y-1">
                                        <span class="block px-4 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Nylo Framework') }}</span>
                                        <a href="https://github.com/nylo-core/nylo/tree/6.x" target="_blank" class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-900 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                                            {{ __('Nylo v7 Framework') }}
                                        </a>
                                        <span class="block px-4 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-2">{{ __('Packages') }}</span>
                                        @foreach(config('project.packages-index') as $package)
                                        <a href="https://pub.dev/packages/{{ str($package['link'])->replace('-', '_') }}" target="_blank" class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-900 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                            {{ $package['label'] }}
                                        </a>
                                        @endforeach
                                    </div>
                                </li>

                                {{-- Resources (Expandable) --}}
                                <li>
                                    <button @click="resourcesOpen = !resourcesOpen" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-3 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                            {{ __('Resources') }}
                                        </span>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': resourcesOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    {{-- Resources Submenu --}}
                                    <div x-show="resourcesOpen" x-collapse class="ml-4 mt-1 space-y-1">
                                        <span class="block px-4 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Developers') }}</span>
                                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}" class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                                            {{ __('Documentation') }}
                                        </a>
                                        <a href="https://github.com/nylo-core/nylo/releases" target="_blank" class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                                            {{ __('Release Notes') }}
                                        </a>
                                        <span class="block px-4 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-2">{{ __('Resources') }}</span>
                                        <a href="https://www.youtube.com/@nylo_dev" target="_blank" class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                                            {{ __('YouTube') }}
                                        </a>
                                        <a href="https://github.com/nylo-core/nylo/discussions" target="_blank" class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                                            {{ __('Forum') }}
                                        </a>
                                        <a href="https://opencollective.com/nylo" target="_blank" class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                                            {{ __('Become a Sponsor') }}
                                        </a>
                                    </div>
                                </li>
                            </ul>

                            {{-- Get Started CTA --}}
                            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo]) }}" class="flex items-center justify-center w-full bg-primary-blue-deep hover:bg-primary-blue px-4 py-3 rounded-lg text-white font-medium transition-colors">
                                    {{ __('Get Started') }}
                                </a>
                            </div>

                            {{-- GitHub Link --}}
                            <a href="https://github.com/nylo-core/nylo" target="_blank" class="flex items-center justify-center mt-4 px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <span class="iconify lucide--github shrink-0 size-5 mr-2" aria-hidden="true"></span>
                                {{ __('Star on GitHub') }}
                                <span class="ml-2 text-slate-500 dark:text-slate-400">{{ $githubStars }}</span>
                            </a>
                        </nav>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function copyCommand(button) {
    const command = button.getAttribute('data-command');
    const iconContainer = button.querySelector('.copy-icon').parentElement;
    const copyIcon = iconContainer.querySelector('.copy-icon');
    const checkIcon = iconContainer.querySelector('.check-icon');

    function fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            return true;
        } catch (err) {
            return false;
        } finally {
            textArea.remove();
        }
    }

    async function doCopy() {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(command);
        } else {
            fallbackCopy(command);
        }

        copyIcon.classList.add('hidden');
        checkIcon.classList.remove('hidden');
        iconContainer.classList.add('text-green-500');

        // Show the instructions after copying
        const instructions = button.querySelector('.copy-instructions');
        if (instructions) {
            instructions.classList.remove('hidden');
        }

        setTimeout(() => {
            copyIcon.classList.remove('hidden');
            checkIcon.classList.add('hidden');
            iconContainer.classList.remove('text-green-500');
        }, 2000);
    }

    doCopy();
}
</script>
