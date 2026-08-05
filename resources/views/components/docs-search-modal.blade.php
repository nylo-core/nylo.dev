@php
$latestVersion = $latestVersionOfNylo;

$links = [
    ['label' => __('Docs'), 'url' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersion, 'page' => 'installation']), 'icon' => 'lucide--book-open', 'description' => __('Get started with Nylo documentation')],
    ['label' => __('Resources'), 'url' => route('landing.resources'), 'icon' => 'lucide--folder', 'description' => __('Videos, forum, sponsors')],
    ['label' => __('GitHub'), 'url' => 'https://github.com/nylo-core/nylo', 'icon' => 'lucide--github', 'description' => __('View source code'), 'external' => true],
    ['label' => __('Release Notes'), 'url' => 'https://github.com/nylo-core/nylo/releases', 'icon' => 'lucide--file-text', 'description' => __('Latest updates and changes'), 'external' => true],
];

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
         class="fixed inset-0 z-[80] flex items-start justify-center pt-[10vh] sm:pt-[14vh] bg-black/50 backdrop-blur-sm"
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
             x-data="nyDocsSearch()"
             @keydown.arrow-down.prevent="navigateDown()"
             @keydown.arrow-up.prevent="navigateUp()"
             @keydown.enter.prevent="selectItem()"
             role="dialog" aria-modal="true" aria-label="{{ __('Search documentation...') }}"
             class="w-[calc(100vw-2rem)] max-w-2xl bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col max-h-[min(70vh,32rem)]">

            {{-- Search Input --}}
            <div class="flex items-center px-4 border-b border-slate-200 dark:border-slate-700">
                <span x-show="!loading" class="iconify lucide--search shrink-0 size-5 text-slate-400" aria-hidden="true"></span>
                <span x-show="loading" x-cloak class="iconify lucide--loader-circle shrink-0 size-5 text-slate-400 animate-spin" aria-hidden="true"></span>
                <input type="text"
                       x-model="query"
                       x-ref="searchInput"
                       @input="onInput()"
                       placeholder="{{ __('Search documentation...') }}"
                       autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                       class="flex-1 h-14 px-4 text-base bg-transparent border-0 outline-none focus:ring-0 text-slate-900 dark:text-slate-100 placeholder-slate-400">
                <div class="flex items-center gap-2">
                    <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-medium text-slate-400 bg-slate-100 dark:bg-slate-700 rounded">esc</kbd>
                    <button @click="$store.search.close()" class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" aria-label="{{ __('Close') }}">
                        <span class="iconify lucide--x shrink-0 size-5 text-slate-400" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            {{-- Results --}}
            <div class="overflow-y-auto flex-1 p-2 ny-docs-search-hits">

                {{-- Default view when no query --}}
                <template x-if="!query.trim()">
                    <div class="space-y-4">
                        {{-- Recent Section --}}
                        <div x-show="recent.length">
                            <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Recent') }}</div>
                            <div class="space-y-1">
                                <template x-for="(item, i) in recent" :key="item.url">
                                    <a :href="item.url"
                                       :data-ny-idx="i"
                                       @mouseenter="$store.search.selectedIndex = i"
                                       @click="$store.search.close()"
                                       :class="{ 'bg-slate-100 dark:bg-slate-700': $store.search.selectedIndex === i }"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors group">
                                        <span class="iconify lucide--clock shrink-0 size-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" aria-hidden="true"></span>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium truncate" x-text="item.title"></div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400 truncate" x-show="item.sub" x-text="item.sub"></div>
                                        </div>
                                        <button @click.prevent.stop="removeRecent(i)"
                                                class="p-1 rounded opacity-0 group-hover:opacity-100 hover:bg-slate-200 dark:hover:bg-slate-600 transition-opacity"
                                                aria-label="{{ __('Close') }}" tabindex="-1">
                                            <span class="iconify lucide--x shrink-0 size-3.5 text-slate-400" aria-hidden="true"></span>
                                        </button>
                                    </a>
                                </template>
                            </div>
                        </div>

                        {{-- Links Section --}}
                        <div>
                            <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Links') }}</div>
                            <div class="space-y-1">
                                @foreach($links as $index => $link)
                                <a href="{{ $link['url'] }}"
                                   @if(isset($link['external']) && $link['external']) target="_blank" rel="noopener" @endif
                                   :data-ny-idx="recent.length + {{ $index }}"
                                   @mouseenter="$store.search.selectedIndex = recent.length + {{ $index }}"
                                   @click="$store.search.close()"
                                   :class="{ 'bg-slate-100 dark:bg-slate-700': $store.search.selectedIndex === recent.length + {{ $index }} }"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors group">
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
                            <div class="flex gap-2 px-3 pb-1">
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

                {{-- Algolia results when a query exists --}}
                <template x-if="query.trim()">
                    <div class="space-y-4">
                        <template x-for="group in groups" :key="group.page">
                            <div>
                                <div class="px-3 py-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider" x-text="group.page"></div>
                                <div class="space-y-1">
                                    <template x-for="item in group.items" :key="item.idx">
                                        <a :href="item.url"
                                           :data-ny-idx="item.idx"
                                           @mouseenter="$store.search.selectedIndex = item.idx"
                                           @click="remember(item); $store.search.close()"
                                           :class="{ 'bg-slate-100 dark:bg-slate-700': $store.search.selectedIndex === item.idx }"
                                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors group">
                                            <span :class="'iconify ' + item.icon" class="shrink-0 size-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" aria-hidden="true"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium truncate" x-html="item.title"></div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400 truncate" x-show="item.sub" x-html="item.sub"></div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Searching (first load) --}}
                        <div x-show="loading && !groups.length" class="px-3 py-10 flex justify-center">
                            <span class="iconify lucide--loader-circle size-7 text-slate-300 dark:text-slate-600 animate-spin" aria-hidden="true"></span>
                        </div>

                        {{-- No results --}}
                        <template x-if="!loading && searched && !groups.length">
                            <div class="px-3 py-8 text-center text-slate-500 dark:text-slate-400">
                                <span class="iconify lucide--search-x size-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" aria-hidden="true"></span>
                                <p>{{ __('No results found for') }} "<span x-text="query.trim()" class="font-medium"></span>"</p>
                            </div>
                        </template>

                        {{-- Algolia attribution (required by the DocSearch program) --}}
                        <div x-show="groups.length" class="flex justify-end px-3 pb-1">
                            <a href="https://www.algolia.com/?utm_source=nylo-docs&utm_medium=referral&utm_content=powered_by" target="_blank" rel="noopener"
                               class="text-[11px] text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-400">
                                Search by <span class="font-semibold">Algolia</span>
                            </a>
                        </div>
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
function nyDocsSearch() {
    const ALGOLIA = {
        appId: @json(config('project.meta.algolia_app_id')),
        apiKey: @json(config('project.meta.algolia_app_key')),
        index: @json(config('project.meta.algolia_index_name')),
        locale: @json(app()->getLocale()),
    };
    const RECENT_KEY = 'nyloDocsRecent';

    return {
        query: '',
        groups: [],
        total: 0,
        loading: false,
        searched: false,
        recent: [],
        links: @json($links),
        _timer: null,
        _abort: null,

        init() {
            try {
                this.recent = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]');
            } catch (e) {
                this.recent = [];
            }
            this.$watch('$store.search.open', (open) => {
                if (open) {
                    this.$nextTick(() => {
                        this.$refs.searchInput.focus();
                        this.$refs.searchInput.select();
                    });
                }
            });
        },

        onInput() {
            this.$store.search.selectedIndex = 0;
            this.searched = false;
            clearTimeout(this._timer);

            const q = this.query.trim();
            if (!q) {
                this.groups = [];
                this.total = 0;
                this.loading = false;
                return;
            }

            this.loading = true;
            this._timer = setTimeout(() => this.fetchResults(q), 180);
        },

        async fetchResults(q) {
            if (this._abort) this._abort.abort();
            this._abort = new AbortController();

            try {
                const res = await fetch('https://' + ALGOLIA.appId + '-dsn.algolia.net/1/indexes/' + encodeURIComponent(ALGOLIA.index) + '/query', {
                    method: 'POST',
                    headers: {
                        'X-Algolia-Application-Id': ALGOLIA.appId,
                        'X-Algolia-API-Key': ALGOLIA.apiKey,
                        'Content-Type': 'application/json',
                    },
                    signal: this._abort.signal,
                    body: JSON.stringify({
                        query: q,
                        hitsPerPage: 20,
                        facetFilters: ['version:latest', 'language:' + ALGOLIA.locale],
                        attributesToRetrieve: ['hierarchy', 'content', 'url', 'type'],
                        attributesToSnippet: ['content:12'],
                        snippetEllipsisText: '…',
                        highlightPreTag: '<mark>',
                        highlightPostTag: '</mark>',
                    }),
                });

                if (!res.ok) throw new Error('Algolia HTTP ' + res.status);
                const data = await res.json();

                // A newer keystroke owns the UI now.
                if (this.query.trim() !== q) return;

                this.setResults(data.hits || []);
            } catch (err) {
                if (err.name === 'AbortError') return;
                if (this.query.trim() !== q) return;
                this.groups = [];
                this.total = 0;
                this.searched = true;
            } finally {
                if (this.query.trim() === q) this.loading = false;
            }
        },

        setResults(hits) {
            const groups = [];
            const byPage = new Map();
            let idx = 0;

            for (const hit of hits) {
                const item = this.formatHit(hit);
                if (!item) continue;
                item.idx = idx++;

                if (!byPage.has(item.page)) {
                    const group = { page: item.page, items: [] };
                    byPage.set(item.page, group);
                    groups.push(group);
                }
                byPage.get(item.page).items.push(item);
            }

            this.groups = groups;
            this.total = idx;
            this.searched = true;
            this.$store.search.selectedIndex = 0;
        },

        formatHit(hit) {
            const h = hit.hierarchy || {};
            const levels = ['lvl0', 'lvl1', 'lvl2', 'lvl3', 'lvl4', 'lvl5', 'lvl6'].filter((l) => h[l] != null && h[l] !== '');
            if (!levels.length && !hit.content) return null;

            const highlighted = (hit._highlightResult && hit._highlightResult.hierarchy) || {};
            const value = (l) => (highlighted[l] && highlighted[l].value) || this.escape(h[l] || '');
            const plainPath = (list) => list.map((l) => this.escape(h[l])).join(' › ');
            const snippet = hit._snippetResult && hit._snippetResult.content && hit._snippetResult.content.value;

            // The index's lvl0 is a constant ("Documentation"); the page title
            // lives in lvl1, so group by that and path relative to it.
            const deepest = levels[levels.length - 1];
            let icon, title, sub;

            if (hit.type === 'content') {
                icon = 'lucide--align-left';
                title = snippet || this.escape(hit.content || '');
                sub = plainPath(levels.slice(2));
            } else if (deepest === 'lvl0' || deepest === 'lvl1') {
                icon = 'lucide--file-text';
                title = value(deepest);
                sub = snippet || plainPath(levels.slice(2, -1));
            } else {
                icon = 'lucide--hash';
                title = value(deepest);
                sub = plainPath(levels.slice(2, -1)) || (snippet || '');
            }

            return {
                icon: icon,
                title: title,
                sub: sub,
                url: this.cleanUrl(hit),
                page: h.lvl1 || h.lvl0 || @json(__('Documentation')),
            };
        },

        // Ported from the old DocSearch transformItems: the crawler's anchors
        // don't always match the docs' real heading ids, so rebuild them from
        // the hierarchy the same way the headings build theirs.
        cleanUrl(hit) {
            const h = hit.hierarchy || {};
            let url = hit.url || '';

            // Keep results on the current host (prod-indexed urls work locally too).
            try {
                const parsed = new URL(url);
                url = parsed.pathname + parsed.hash;
            } catch (e) { /* already relative */ }

            url = url.replace(/#.*$/, '');

            let section = h.lvl3 ?? h.lvl2 ?? h.lvl4 ?? null;
            if (section == null && h.lvl5 != null) {
                if (['Getting Started', 'Basics', 'Introduction', 'Advanced', 'Widgets'].includes(h.lvl5)) {
                    return url;
                }
                section = h.lvl5;
            }
            if (!section) return url;

            const id = section.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
            return url + '#' + id;
        },

        escape(value) {
            const div = document.createElement('div');
            div.textContent = String(value);
            return div.innerHTML;
        },

        get totalNavigable() {
            return this.query.trim() ? this.total : this.recent.length + this.links.length;
        },

        navigateDown() {
            if (this.$store.search.selectedIndex < this.totalNavigable - 1) {
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
                const selected = this.$el.querySelector('[data-ny-idx="' + this.$store.search.selectedIndex + '"]');
                if (selected) selected.scrollIntoView({ block: 'nearest' });
            });
        },

        selectItem() {
            const idx = this.$store.search.selectedIndex;

            if (!this.query.trim()) {
                if (idx < this.recent.length) {
                    this.$store.search.close();
                    window.location.href = this.recent[idx].url;
                    return;
                }
                const link = this.links[idx - this.recent.length];
                if (!link) return;
                this.$store.search.close();
                if (link.external) {
                    window.open(link.url, '_blank');
                } else {
                    window.location.href = link.url;
                }
                return;
            }

            for (const group of this.groups) {
                for (const item of group.items) {
                    if (item.idx === idx) {
                        this.remember(item);
                        this.$store.search.close();
                        window.location.href = item.url;
                        return;
                    }
                }
            }
        },

        remember(item) {
            const entry = {
                title: this.strip(item.title),
                sub: this.strip(item.sub),
                url: item.url,
            };
            this.recent = [entry, ...this.recent.filter((r) => r.url !== entry.url)].slice(0, 5);
            localStorage.setItem(RECENT_KEY, JSON.stringify(this.recent));
        },

        removeRecent(i) {
            this.recent.splice(i, 1);
            localStorage.setItem(RECENT_KEY, JSON.stringify(this.recent));
            this.$store.search.selectedIndex = 0;
        },

        strip(html) {
            const div = document.createElement('div');
            div.innerHTML = html || '';
            return div.textContent;
        },
    };
}
</script>
