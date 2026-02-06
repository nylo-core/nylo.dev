<footer class="bg-gray-50 dark:bg-slate-800/50 transition-colors duration-300 border-t border-gray-200 dark:border-slate-800 bg-gradient-to-b from-[#f8fcfd] dark:from-slate-900/50 to-transparent">
    <div class="container mx-auto px-6 lg:px-32 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
            {{-- Brand --}}
            <div class="col-span-2 md:col-span-1">
                <img src="{{ asset('images/nylo_logo_filled.png') }}" alt="{{ config('app.name') }} logo" class="h-16 mb-5 dark:brightness-0 dark:invert">
                <p class="text-gray-500 dark:text-slate-400 text-sm leading-relaxed">{{ config('app.name') }} {{ __('is a micro-framework for Flutter designed to help simplify developing apps.') }}</p>
            </div>

            {{-- Documentation --}}
            <div>
                <h5 class="font-semibold text-gray-900 dark:text-white mb-5">{{ __('Documentation') }}</h5>
                <ul class="space-y-3">
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}">{{ __('Installation') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'requirements']) }}">{{ __('Requirements') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'router']) }}">{{ __('Router') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'themes-and-styling']) }}">{{ __('Themes & Styling') }}</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div>
                <h5 class="font-semibold text-gray-900 dark:text-white mb-5">{{ __('Resources') }}</h5>
                <ul class="space-y-3">
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK">{{ __('Flutter Packages') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="https://github.com/nylo-core/nylo">{{ __('Contributions') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="{{ route('landing.privacy-policy', ['locale' => app()->getLocale()]) }}">{{ __('Privacy Policy') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="{{ route('landing.terms-and-conditions', ['locale' => app()->getLocale()]) }}">{{ __('Terms & Conditions') }}</a></li>
                </ul>
            </div>

            {{-- Community --}}
            <div>
                <h5 class="font-semibold text-gray-900 dark:text-white mb-5">{{ __('Community') }}</h5>
                <ul class="space-y-3">
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">{{ __('Discussions') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="https://twitter.com/nylo_dev" target="_BLANK">{{ __('Twitter') }}</a></li>
                    <li><a class="text-gray-500 dark:text-slate-400 hover:text-primary-blue dark:hover:text-primary-blue transition-colors text-sm" href="https://www.youtube.com/@nylo_dev" target="_BLANK">{{ __('YouTube') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-gray-200 dark:border-slate-700">
        <div class="container mx-auto px-6 lg:px-32 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <span class="text-gray-400 dark:text-slate-500 text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All Rights Reserved') }}</span>

                <div class="flex items-center gap-6">
                    {{-- Language Switcher --}}
                    @if(Route::currentRouteName() && str_starts_with(Route::currentRouteName(), 'landing.'))
                        <x-language-switcher />
                    @endif

                    {{-- Social Links --}}
                    <div class="flex gap-5">
                        <a href="https://twitter.com/nylo_dev" target="_BLANK" class="text-gray-400 hover:text-primary-blue transition-colors">
                            <i class="ri-twitter-fill text-xl"></i>
                        </a>
                        <a href="https://github.com/nylo-core/nylo" target="_BLANK" class="text-gray-400 hover:text-primary-blue transition-colors">
                            <i class="ri-github-fill text-xl"></i>
                        </a>
                        <a href="https://www.youtube.com/@nylo_dev" target="_BLANK" class="text-gray-400 hover:text-primary-blue transition-colors">
                            <i class="ri-youtube-fill text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
