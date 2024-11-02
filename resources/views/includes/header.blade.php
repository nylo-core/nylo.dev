<div class="text-white text-center py-2 isolate z-10" style="background-image: linear-gradient(129deg, #3b87c4, #3c87c3, #5491bc, #1195b5, #03a4b0);">
    <p class="text-sm">Out in the wild <span class="font-medium">Nylo v6</span> 🔥 <a href="{{ route('learn-more.v6') }}" class="underline hover:text-gray-200 transition-all">Learn more</a></p>
</div>


<header class="backdrop-blur bg-white/90 dark:bg-transparent dark:border-slate-50/[0.06] duration-500 flex-none lg:border-slate-900/10 lg:z-50 sticky supports-backdrop-blur:bg-white/60 top-0 transition-colors w-full z-40" style="position: relative; max-height: 83px;">
    <div class="max-w-8xl mx-auto">
        <div class="border-b border-slate-900/10 dark:border-slate-300/10 lg:border-0 lg:mx-0 lg:px-32 py-4">
            <div class="relative flex items-center justify-between mr-5 md:mr-5 md:justify-start">
                <a class="mr-3 flex-none overflow-hidden md:w-auto" href="{{ route('landing.index') }}">
                    <img src="{{ asset('images/nylo_logo_filled.png') }}" class="h-[50px]" alt="{{ config('app.name') }} logo" />
                </a>

                <div class="relative hidden sm:flex items-center ml-auto">
                    <nav class="leading-6 text-slate-700 dark:text-slate-200">
                        <ul class="flex space-x-8">
                            <li>
                                <a class="flex hover:text-gray-400 transition-all text-gray-600" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'installation']) }}">Documentation</a>
                            </li>
                            <li>
                                <a class="flex hover:text-gray-400 transition-all text-gray-600" href="{{ route('ecosystem.index') }}">Ecosystem</a>
                            </li>
                            <li>
                                <a href="{{ route('resources.index') }}" class="hover:text-gray-400 transition-all text-gray-600">Resources</a>
                            </li>

                            <li>
                                <a class="flex hover:text-gray-400 transition-all text-gray-600" href="https://github.com/nylo-core" target="_BLANK">
                                    <span>Packages</span>

                                    <span class="ml-2 self-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="9" viewBox="0 0 13 13" fill="none">
<path d="M12.9199 0.62C12.8185 0.375651 12.6243 0.181475 12.3799 0.0799999C12.2597 0.028759 12.1306 0.00157999 11.9999 0H1.99994C1.73472 0 1.48037 0.105357 1.29283 0.292893C1.1053 0.48043 0.999939 0.734784 0.999939 1C0.999939 1.26522 1.1053 1.51957 1.29283 1.70711C1.48037 1.89464 1.73472 2 1.99994 2H9.58994L1.28994 10.29C1.19621 10.383 1.12182 10.4936 1.07105 10.6154C1.02028 10.7373 0.994141 10.868 0.994141 11C0.994141 11.132 1.02028 11.2627 1.07105 11.3846C1.12182 11.5064 1.19621 11.617 1.28994 11.71C1.3829 11.8037 1.4935 11.8781 1.61536 11.9289C1.73722 11.9797 1.86793 12.0058 1.99994 12.0058C2.13195 12.0058 2.26266 11.9797 2.38452 11.9289C2.50638 11.8781 2.61698 11.8037 2.70994 11.71L10.9999 3.41V11C10.9999 11.2652 11.1053 11.5196 11.2928 11.7071C11.4804 11.8946 11.7347 12 11.9999 12C12.2652 12 12.5195 11.8946 12.707 11.7071C12.8946 11.5196 12.9999 11.2652 12.9999 11V1C12.9984 0.869323 12.9712 0.740222 12.9199 0.62Z" fill="#9ca3af"/>
</svg>
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </nav>

                    <div class="flex items-center ml-6 pl-6 dark:border-slate-800">
                        <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}" class="hidden md:block bg-primary-blue-deep transition-all hover:text-white/70 ml-1 px-4 py-2 rounded text-white" target="_BLANK">Get Started</a>
                    </div>
                </div>

                <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}" class="sm:hidden bg-primary-blue-deep block transition-all hover:text-white/70 ml-6 px-4 py-2 rounded text-white" target="_BLANK">Get Started</a>
            </div>
        </div>
    </div>
</header>
