@php
$latestVersion = $latestVersionOfNylo;

$links = [
    ['label' => __('Docs'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'installation']), 'icon' => 'lucide--book-open', 'description' => __('Get started with Nylo documentation')],
    ['label' => __('Resources'), 'url' => route('landing.resources'), 'icon' => 'lucide--folder', 'description' => __('Videos, forum, sponsors')],
    ['label' => __('GitHub'), 'url' => 'https://github.com/nylo-core/nylo', 'icon' => 'lucide--github', 'description' => __('View source code'), 'external' => true],
    ['label' => __('Release Notes'), 'url' => 'https://github.com/nylo-core/nylo/releases', 'icon' => 'lucide--file-text', 'description' => __('Latest updates and changes'), 'external' => true],
];

$docs = [
    // Introduction
    ['label' => __('What is Nylo'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'what-is-nylo']), 'section' => __('Introduction'), 'description' => __('Introduction to Nylo framework')],
    ['label' => __('Requirements'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'requirements']), 'section' => __('Introduction'), 'description' => __('System requirements')],
    ['label' => __('Contributions'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'contributions']), 'section' => __('Introduction'), 'description' => __('How to contribute')],

    // Getting Started
    ['label' => __('Installation'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'installation']), 'section' => __('Getting Started'), 'description' => __('Install Nylo')],
    ['label' => __('Configuration'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'configuration']), 'section' => __('Getting Started'), 'description' => __('Configure your app')],
    ['label' => __('Directory Structure'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'directory-structure']), 'section' => __('Getting Started'), 'description' => __('Project layout')],
    ['label' => __('Upgrade Guide'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'upgrade-guide']), 'section' => __('Getting Started'), 'description' => __('Upgrade to latest version')],

    // Basics
    ['label' => __('Router'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'router']), 'section' => __('Basics'), 'description' => __('Navigation and routing')],
    ['label' => __('Networking'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'networking']), 'section' => __('Basics'), 'description' => __('API calls and HTTP requests')],
    ['label' => __('Metro'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'metro']), 'section' => __('Basics'), 'description' => __('CLI tool for Nylo')],
    ['label' => __('Localization'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'localization']), 'section' => __('Basics'), 'description' => __('Multi-language support')],
    ['label' => __('Storage'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'storage']), 'section' => __('Basics'), 'description' => __('Data persistence')],
    ['label' => __('Controllers'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'controllers']), 'section' => __('Basics'), 'description' => __('Page controllers')],
    ['label' => __('App Icons'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'app-icons']), 'section' => __('Basics'), 'description' => __('App icon configuration')],
    ['label' => __('Validation'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'validation']), 'section' => __('Basics'), 'description' => __('Form validation')],
    ['label' => __('Authentication'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'authentication']), 'section' => __('Basics'), 'description' => __('User authentication')],
    ['label' => __('Logging'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'logging']), 'section' => __('Basics'), 'description' => __('Debug logging')],
    ['label' => __('Forms'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'forms']), 'section' => __('Basics'), 'description' => __('Form handling')],
    ['label' => __('Cache'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'cache']), 'section' => __('Basics'), 'description' => __('Caching data')],

    // Widgets
    ['label' => __('Themes and Styling'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'themes-and-styling']), 'section' => __('Widgets'), 'description' => __('App theming')],
    ['label' => __('Assets'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'assets']), 'section' => __('Widgets'), 'description' => __('Asset management')],
    ['label' => __('Navigation Hub'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'navigation-hub']), 'section' => __('Widgets'), 'description' => __('Navigation widget')],
    ['label' => __('NyState'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'ny-state']), 'section' => __('Widgets'), 'description' => __('State widget')],
    ['label' => __('NyFutureBuilder'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'ny-future-builder']), 'section' => __('Widgets'), 'description' => __('Async builder widget')],
    ['label' => __('NyTextField'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'ny-text-field']), 'section' => __('Widgets'), 'description' => __('Text input widget')],
    ['label' => __('NyPullToRefresh'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'ny-pull-to-refresh']), 'section' => __('Widgets'), 'description' => __('Pull to refresh widget')],
    ['label' => __('NyListView'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'ny-list-view']), 'section' => __('Widgets'), 'description' => __('List view widget')],
    ['label' => __('NyLanguageSwitcher'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'ny-language-switcher']), 'section' => __('Widgets'), 'description' => __('Language switcher widget')],

    // Advanced
    ['label' => __('State Management'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'state-management']), 'section' => __('Advanced'), 'description' => __('Global state management')],
    ['label' => __('Push Notifications'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'push-notifications']), 'section' => __('Advanced'), 'description' => __('Firebase push notifications')],
    ['label' => __('Providers'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'providers']), 'section' => __('Advanced'), 'description' => __('Service providers')],
    ['label' => __('Decoders'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'decoders']), 'section' => __('Advanced'), 'description' => __('Model decoders')],
    ['label' => __('Events'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'events']), 'section' => __('Advanced'), 'description' => __('Event system')],
    ['label' => __('App Usage'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'app-usage']), 'section' => __('Advanced'), 'description' => __('Track app usage')],
    ['label' => __('Scheduler'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'scheduler']), 'section' => __('Advanced'), 'description' => __('Task scheduling')],
];

$packages = config('project.packages-index');

$themes = [
    ['label' => __('System'), 'action' => 'system', 'icon' => 'lucide--monitor'],
    ['label' => __('Light'), 'action' => 'light', 'icon' => 'lucide--sun'],
    ['label' => __('Dark'), 'action' => 'dark', 'icon' => 'lucide--moon'],
];
@endphp

<template x-teleport="body">
    <div x-show="$store.search.open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="$store.search.close()"
         @keydown.escape.window="$store.search.close()"
         class="fixed inset-0 z-50 flex items-start justify-center pt-[10vh] sm:pt-[15vh] bg-black/50 backdrop-blur-sm"
         style="display: none;"
         x-cloak>

        <div x-show="$store.search.open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.stop
             x-data="globalSearch()"
             @keydown.arrow-down.prevent="navigateDown()"
             @keydown.arrow-up.prevent="navigateUp()"
             @keydown.enter.prevent="selectItem()"
             class="w-[calc(100vw-2rem)] max-w-2xl bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col max-h-[28rem]">

            {{-- Search Input --}}
            <div class="flex items-center px-4 border-b border-slate-200 dark:border-slate-700">
                <span class="iconify lucide--search shrink-0 size-5 text-slate-400" aria-hidden="true"></span>
                <input type="text"
                       x-model="query"
                       x-ref="searchInput"
                       @input="$store.search.query = query; $store.search.selectedIndex = 0"
                       x-init="$watch('$store.search.open', value => { if(value) { $nextTick(() => $refs.searchInput.focus()); } })"
                       placeholder="{{ __('Search documentation...') }}"
                       class="flex-1 h-14 px-4 text-base bg-transparent border-0 outline-none focus:ring-0 text-slate-900 dark:text-slate-100 placeholder-slate-400">
                <div class="flex items-center gap-2">
                    <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-medium text-slate-400 bg-slate-100 dark:bg-slate-700 rounded">esc</kbd>
                    <button @click="$store.search.close()" class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <span class="iconify lucide--x shrink-0 size-5 text-slate-400" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            {{-- Results --}}
            <div class="overflow-y-auto flex-1 p-2">
                {{-- Default view when no query --}}
                <template x-if="!query">
                    <div class="space-y-4">
                        {{-- Links Section --}}
                        <div>
                            <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Links') }}</div>
                            <div class="space-y-1">
                                @foreach($links as $index => $link)
                                <a href="{{ $link['url'] }}"
                                   @if(isset($link['external']) && $link['external']) target="_blank" rel="noopener" @endif
                                   @mouseenter="$store.search.selectedIndex = {{ $index }}"
                                   @click="$store.search.close()"
                                   :class="{ 'bg-slate-100 dark:bg-slate-700': $store.search.selectedIndex === {{ $index }} }"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors group"
                                   data-index="{{ $index }}"
                                   data-type="link">
                                    <span class="iconify {{ $link['icon'] }} shrink-0 size-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" aria-hidden="true"></span>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium">{{ $link['label'] }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $link['description'] }}</div>
                                    </div>
                                    @if(isset($link['external']) && $link['external'])
                                    <span class="iconify lucide--external-link shrink-0 size-4 text-slate-400" aria-hidden="true"></span>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Theme Section --}}
                        <div>
                            <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Theme') }}</div>
                            <div class="flex gap-2 px-3">
                                @foreach($themes as $theme)
                                <button @click="setTheme('{{ $theme['action'] }}'); $store.search.close()"
                                        class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-600">
                                    <span class="iconify {{ $theme['icon'] }} shrink-0 size-4" aria-hidden="true"></span>
                                    <span class="text-sm font-medium">{{ $theme['label'] }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Search results when query exists --}}
                <template x-if="query">
                    <div class="space-y-4">
                        {{-- Filtered Links --}}
                        <template x-if="filteredLinks.length > 0">
                            <div>
                                <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Links') }}</div>
                                <div class="space-y-1">
                                    <template x-for="(item, index) in filteredLinks" :key="item.label">
                                        <a :href="item.url"
                                           :target="item.external ? '_blank' : '_self'"
                                           @mouseenter="$store.search.selectedIndex = index"
                                           @click="$store.search.close()"
                                           :class="{ 'bg-slate-100 dark:bg-slate-700': $store.search.selectedIndex === index }"
                                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors group">
                                            <span :class="'iconify ' + item.icon" class="shrink-0 size-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" aria-hidden="true"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium" x-text="item.label"></div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400 truncate" x-text="item.description"></div>
                                            </div>
                                            <template x-if="item.external">
                                                <span class="iconify lucide--external-link shrink-0 size-4 text-slate-400" aria-hidden="true"></span>
                                            </template>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Filtered Docs --}}
                        <template x-if="filteredDocs.length > 0">
                            <div>
                                <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Documentation') }}</div>
                                <div class="space-y-1">
                                    <template x-for="(item, index) in filteredDocs" :key="item.label">
                                        <a :href="item.url"
                                           @mouseenter="$store.search.selectedIndex = filteredLinks.length + index"
                                           @click="$store.search.close()"
                                           :class="{ 'bg-slate-100 dark:bg-slate-700': $store.search.selectedIndex === filteredLinks.length + index }"
                                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors group">
                                            <span class="iconify lucide--file-text shrink-0 size-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" aria-hidden="true"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium">
                                                    <span class="text-slate-400" x-text="item.section + ' >'"></span>
                                                    <span x-text="item.label"></span>
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400 truncate" x-text="item.description"></div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Filtered Packages --}}
                        <template x-if="filteredPackages.length > 0">
                            <div>
                                <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Packages') }}</div>
                                <div class="space-y-1">
                                    <template x-for="(item, index) in filteredPackages" :key="item.label">
                                        <a :href="'https://pub.dev/packages/' + item.link.replace(/-/g, '_')"
                                           target="_blank"
                                           rel="noopener"
                                           @mouseenter="$store.search.selectedIndex = filteredLinks.length + filteredDocs.length + index"
                                           @click="$store.search.close()"
                                           :class="{ 'bg-slate-100 dark:bg-slate-700': $store.search.selectedIndex === filteredLinks.length + filteredDocs.length + index }"
                                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors group">
                                            <span class="iconify lucide--package shrink-0 size-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" aria-hidden="true"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium" x-text="item.label"></div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400 truncate" x-text="item.description"></div>
                                            </div>
                                            <span class="iconify lucide--external-link shrink-0 size-4 text-slate-400" aria-hidden="true"></span>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- No results --}}
                        <template x-if="filteredLinks.length === 0 && filteredDocs.length === 0 && filteredPackages.length === 0">
                            <div class="px-3 py-8 text-center text-slate-500 dark:text-slate-400">
                                <span class="iconify lucide--search-x size-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" aria-hidden="true"></span>
                                <p>{{ __('No results found for') }} "<span x-text="query" class="font-medium"></span>"</p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between px-4 py-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 bg-white dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600 font-medium">&uarr;</kbd>
                        <kbd class="px-1.5 py-0.5 bg-white dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600 font-medium">&darr;</kbd>
                        <span class="ml-1">{{ __('Navigate') }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 bg-white dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600 font-medium">&crarr;</kbd>
                        <span class="ml-1">{{ __('Select') }}</span>
                    </span>
                </div>
                <span class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 bg-white dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600 font-medium">
                        <span x-text="navigator.platform.indexOf('Mac') > -1 ? '&#8984;' : 'Ctrl'"></span>
                    </kbd>
                    <kbd class="px-1.5 py-0.5 bg-white dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600 font-medium">K</kbd>
                    <span class="ml-1">{{ __('to open') }}</span>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
function globalSearch() {
    return {
        query: '',

        links: @json($links),
        docs: @json($docs),
        packages: @json($packages),

        get filteredLinks() {
            if (!this.query) return this.links;
            const q = this.query.toLowerCase();
            return this.links.filter(item =>
                item.label.toLowerCase().includes(q) ||
                item.description.toLowerCase().includes(q)
            );
        },

        get filteredDocs() {
            if (!this.query) return [];
            const q = this.query.toLowerCase();
            return this.docs.filter(item =>
                item.label.toLowerCase().includes(q) ||
                item.description.toLowerCase().includes(q) ||
                item.section.toLowerCase().includes(q)
            ).slice(0, 10);
        },

        get filteredPackages() {
            if (!this.query) return [];
            const q = this.query.toLowerCase();
            return this.packages.filter(item =>
                item.label.toLowerCase().includes(q) ||
                item.description.toLowerCase().includes(q)
            );
        },

        get totalResults() {
            return this.filteredLinks.length + this.filteredDocs.length + this.filteredPackages.length;
        },

        navigateDown() {
            if (this.$store.search.selectedIndex < this.totalResults - 1) {
                this.$store.search.selectedIndex++;
                this.scrollToSelected();
            }
        },

        navigateUp() {
            if (this.$store.search.selectedIndex > 0) {
                this.$store.search.selectedIndex--;
                this.scrollToSelected();
            }
        },

        scrollToSelected() {
            this.$nextTick(() => {
                const selected = this.$el.querySelector('[class*="bg-slate-100"]');
                if (selected) {
                    selected.scrollIntoView({ block: 'nearest' });
                }
            });
        },

        selectItem() {
            const idx = this.$store.search.selectedIndex;

            if (!this.query) {
                // Default view - only links
                if (idx < this.links.length) {
                    window.location.href = this.links[idx].url;
                }
                return;
            }

            // Search results view
            if (idx < this.filteredLinks.length) {
                const item = this.filteredLinks[idx];
                if (item.external) {
                    window.open(item.url, '_blank');
                } else {
                    window.location.href = item.url;
                }
            } else if (idx < this.filteredLinks.length + this.filteredDocs.length) {
                const docIdx = idx - this.filteredLinks.length;
                window.location.href = this.filteredDocs[docIdx].url;
            } else {
                const pkgIdx = idx - this.filteredLinks.length - this.filteredDocs.length;
                const pkg = this.filteredPackages[pkgIdx];
                window.open('https://pub.dev/packages/' + pkg.link.replace(/-/g, '_'), '_blank');
            }

            this.$store.search.close();
        }
    };
}

</script>
