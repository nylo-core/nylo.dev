<div x-data="{ open: false }" class="relative inline-flex">
    <button
        @click="open = !open"
        @click.away="open = false"
        class="flex items-center gap-1.5 px-3 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400 text-sm"
        aria-label="{{ __('Select language') }}"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
        </svg>
        <span class="font-medium">{{ $locales[$currentLocale]['native'] }}</span>
        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    {{-- Language Selection Container --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute bottom-full right-0 mb-3 w-64 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden"
    >
        {{-- Header --}}
        <div class="px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <span class="font-semibold text-gray-700 dark:text-slate-200 text-sm">{{ __('Select Language') }}</span>
            </div>
        </div>

        {{-- Language Options --}}
        <div class="py-2 max-h-64 overflow-y-auto">
            @foreach($locales as $code => $locale)
                @php
                    $routeName = Route::currentRouteName();
                    $currentParams = Route::current()->parameters();
                    $isActive = $code === $currentLocale;

                    // Map non-localized routes to their localized equivalents
                    $localizedRouteMap = [
                        'landing.home' => 'landing.index',
                    ];

                    // If current route has a localized version, use it
                    if (isset($localizedRouteMap[$routeName])) {
                        $targetRoute = $localizedRouteMap[$routeName];
                        $routeParams = ['locale' => $code];
                    } else {
                        // Already on a localized route, just update the locale
                        $targetRoute = $routeName;
                        $routeParams = array_merge($currentParams, ['locale' => $code]);
                    }
                @endphp
                <a
                    href="{{ route($targetRoute, $routeParams) }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors {{ $isActive ? 'bg-primary-blue/10 text-primary-blue' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700' }}"
                >
                    <span class="flex-1 font-medium">{{ $locale['native'] }}</span>
                    @if($isActive)
                        <svg class="w-4 h-4 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Arrow pointer --}}
        <div class="absolute -bottom-2 right-6 w-4 h-4 bg-white dark:bg-slate-800 border-r border-b border-slate-200 dark:border-slate-700 transform rotate-45"></div>
    </div>
</div>
