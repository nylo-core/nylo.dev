@php
    $docIndex = config('project.doc-index.versions')[$version] ?? [];
    $allPages = [];
    foreach ($docIndex as $sectionKey => $pages) {
        foreach ($pages as $pageSlug) {
            $allPages[] = ['section' => $sectionKey, 'page' => $pageSlug];
        }
    }
    
    $currentIndex = null;
    foreach ($allPages as $index => $item) {
        if ($item['page'] === $page) {
            $currentIndex = $index;
            break;
        }
    }
    
    $prevPage = $currentIndex !== null && $currentIndex > 0 ? $allPages[$currentIndex - 1] : null;
    $nextPage = $currentIndex !== null && $currentIndex < count($allPages) - 1 ? $allPages[$currentIndex + 1] : null;
@endphp

<footer class="not-prose mt-16">
    <!-- Navigation Cards -->
    <div class="flex flex-col sm:flex-row gap-4 mb-12">
        @if($prevPage)
        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'page' => $prevPage['page'], 'version' => $version]) }}"
           class="group flex-1 flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors duration-200">
                <svg class="w-5 h-5 text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">{{ __('Previous') }}</div>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate transition-colors duration-200">
                    @if (str($prevPage['page'])->startsWith('ny'))
                        {{ str($prevPage['page'])->headline()->replace(" ", "") }}
                    @else
                        {{ __(str($prevPage['page'])->headline()->toString()) }}
                    @endif
                </div>
            </div>
        </a>
        @else
        <div class="flex-1"></div>
        @endif

        @if($nextPage)
        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'page' => $nextPage['page'], 'version' => $version]) }}"
           class="group flex-1 flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 sm:flex-row-reverse sm:text-right">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors duration-200">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-medium text-blue-600 dark:text-white/80 uppercase tracking-wide mb-1">{{ __('Next') }}</div>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate transition-colors duration-200">
                    @if (str($nextPage['page'])->startsWith('ny'))
                        {{ str($nextPage['page'])->headline()->replace(" ", "") }}
                    @else
                        {{ __(str($nextPage['page'])->headline()->toString()) }}
                    @endif
                </div>
            </div>
        </a>
        @endif
    </div>

    <!-- Footer Info -->
    <div class="pt-8 pb-16 border-t border-slate-200 dark:border-slate-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Copyright &copy; {{ date('Y') }} {{ config('app.name') }}
            </p>
            <div class="flex items-center gap-6">
                {{-- Language Switcher --}}
                @if(Route::currentRouteName() === 'landing.docs')
                    <x-language-switcher />
                @endif

                <a href="https://www.youtube.com/@nylo_dev" target="_blank" rel="noopener" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 transition-colors duration-150">
                    <span class="sr-only">YouTube</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </a>
                <a href="https://github.com/nylo-core/nylo" target="_blank" rel="noopener" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 transition-colors duration-150">
                    <span class="sr-only">GitHub</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                    </svg>
                </a>
                <a href="https://twitter.com/nylo_dev" target="_blank" rel="noopener" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 transition-colors duration-150">
                    <span class="sr-only">Twitter</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>
