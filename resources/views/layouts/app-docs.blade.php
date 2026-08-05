<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" x-data x-bind:class="{ 'dark': $store.darkMode.on }" class="scroll-smooth">

<head>
    <script>
        (function() {
            var darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'true' || (darkMode === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="{{ config('app.name') }}" />
    {!! SEO::generate(true) !!}

    @include('includes.hreflang')

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    @vite(['resources/css/app.css', 'resources/css/docs.css', 'resources/js/app.js'])

    <meta name="docsearch:language" content="{{ app()->getLocale() }}" />
    @if ($latestVersionOfNylo == $version)
        <meta name="docsearch:version" content="{{ $version }},latest" />
    @else
        <meta name="docsearch:version" content="{{ $version }}" />
    @endif

    @env('production')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('project.meta.ga_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ config('project.meta.ga_id') }}');
    </script>
    @endenv

    <link rel="preconnect" href="https://CN0FWF0JLR-dsn.algolia.net" crossorigin />
</head>

<body class="ny-bg antialiased">

    {{-- Reading progress --}}
    <div class="fixed top-0 left-0 right-0 z-[70] h-0.5 bg-transparent pointer-events-none">
        <div id="reading-progress" class="h-full bg-gradient-to-r from-[#0B5FA8] to-[#3FC0E8] transition-all duration-150 ease-out" style="width: 0%"></div>
    </div>

    <div x-data="{ menuOpen: false }">

    {{-- ===================================================== HEADER ===== --}}
    <header class="ny-doc-header">
        <div class="ny-doc-shell ny-doc-header-inner">
            <a class="flex flex-none items-center gap-2.5 mr-1 lg:mr-2.5" href="{{ route('landing.home') }}">
                <img src="{{ asset('images/nylo_logo.png') }}" alt="" class="ny-mark h-[25px] drop-shadow-[0_2px_5px_rgba(16,118,190,.35)]">
                <span class="ny-wordmark text-[22px]">{{ config('app.name') }}</span>
            </a>
            <span class="ny-doc-badge hidden sm:block">{{ __('DOCS') }}</span>

            <div class="flex-1"></div>

            <nav class="hidden lg:flex items-center gap-[22px] flex-none mr-1.5">
                <a href="{{ route('landing.home') }}" class="ny-nav-link">{{ __('Framework') }}</a>
                <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK" class="ny-nav-link">{{ __('Community') }}</a>
                @if(array_key_exists($version, config('project.doc-tutorials.versions', [])))
                <a href="{{ route('tutorials.index', ['version' => $version]) }}" class="ny-nav-link">{{ __('Tutorials') }}</a>
                @endif
                <a href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK" class="ny-nav-link">
                    {{ __('Packages') }} <span class="text-[10px] ny-c-faint">↗</span>
                </a>
            </nav>

            <div class="flex items-center gap-2 flex-none">
                {{-- Search --}}
                <button @click="$store.search.toggle()" class="ny-icon-btn" aria-label="{{ __('Search the docs') }}">
                    <span class="iconify lucide--search shrink-0 size-4" aria-hidden="true"></span>
                </button>

                <button @click="$store.darkMode.toggle()" class="ny-icon-btn" aria-label="{{ __('Toggle dark mode') }}">
                    <svg x-show="$store.darkMode.on" x-cloak class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg x-show="!$store.darkMode.on" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                <a href="https://github.com/nylo-core/nylo" rel="noopener" target="_blank" class="ny-pill-btn hidden sm:flex h-[33px] px-[11px] text-[12.5px]" aria-label="{{ config('app.name') }} on GitHub">
                    <span class="iconify lucide--star shrink-0 size-3.5 ny-c-body" aria-hidden="true"></span>
                    <span class="truncate">{{ $githubStars ?? '' }}</span>
                </a>
            </div>
        </div>
    </header>

    {{-- ============================================== MOBILE CONTROLS ==== --}}
    <div class="ny-doc-shell">
        <div class="ny-doc-mobilebar">
            <button type="button" @click="menuOpen = true" class="ny-doc-action" aria-label="{{ __('Open navigation') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                {{ __('Menu') }}
            </button>
            <ol class="flex min-w-0 items-center gap-2 font-mono text-[12px] ny-c-muted">
                <li class="truncate">{{ __(str($section)->headline()->toString()) }}</li>
                <li class="ny-doc-crumbs-sep">/</li>
                <li class="truncate ny-doc-crumbs-current">{{ str($page)->headline() }}</li>
            </ol>
        </div>
    </div>

    {{-- ================================================ MOBILE DRAWER ==== --}}
    <template x-teleport="body">
        <div x-show="menuOpen" x-cloak @click="menuOpen = false"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="ny-doc-drawer-backdrop lg:hidden"></div>
    </template>

    <template x-teleport="body">
        <div x-show="menuOpen" x-cloak @keydown.escape.window="menuOpen = false"
             x-transition:enter="transition ease-out duration-250" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="ny-doc-drawer lg:hidden">
            <div class="flex items-center justify-between mb-4">
                <span class="ny-doc-badge">{{ __('DOCS') }}</span>
                <button type="button" @click="menuOpen = false" class="ny-icon-btn" aria-label="{{ __('Close menu') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            @include('docs.sidebar')
        </div>
    </template>

    {{-- ======================================================= BODY ====== --}}
    <div class="ny-doc-shell ny-doc-grid">

        <aside class="ny-doc-sidebar hidden lg:block">
            @include('docs.sidebar')
        </aside>

        <main class="ny-doc-main">
            @if($viewingOldDocs)
            <div class="mb-8 rounded-xl border border-amber-300/60 bg-amber-50 px-5 py-4 text-[14.5px] leading-relaxed text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                <strong class="font-semibold">{{ __('Notice') }}:</strong>
                {{ __("You're viewing an old version of the :name documentation.", ['name' => config('app.name')]) }}
                @if ($page == 'themes' && $version != '5.x')
                    <a class="underline" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'themes-and-styling']) }}">{{ __('Upgrade to :version', ['version' => $latestVersionOfNylo]) }}</a>.
                @else
                    <a class="underline" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => $docsContainPage ? $page : 'installation']) }}">{{ __('Upgrade to :version', ['version' => $latestVersionOfNylo]) }}</a>.
                @endif
            </div>
            @endif

            @yield('content')

            @include('docs.footer')
        </main>

        {{-- ========================================== ON THIS PAGE ======= --}}
        <aside class="ny-doc-toc">
            @if(!empty($docContents['on-this-page']))
            <div class="ny-doc-toc-label">{{ __('On this page') }}</div>
            <nav id="toc-nav" class="ny-doc-toc-list">
                @foreach($docContents['on-this-page'] as $item)
                    @if($item['anchor'])
                        <a href="#{{ $item['anchor'] }}" class="ny-doc-toc-link" data-anchor="{{ $item['anchor'] }}">{{ $item['text'] }}</a>
                    @else
                        <span class="ny-doc-toc-link">{{ $item['text'] }}</span>
                    @endif

                    @foreach($item['children'] ?? [] as $child)
                        @if($child['anchor'])
                            <a href="#{{ $child['anchor'] }}" class="ny-doc-toc-link ny-doc-toc-sub" data-anchor="{{ $child['anchor'] }}">{{ $child['text'] }}</a>
                        @endif
                    @endforeach
                @endforeach
            </nav>
            @endif

            <div class="ny-doc-callout">
                <div class="ny-doc-callout-title">{{ __('Stuck on something?') }}</div>
                <p>{{ __('Ask in Discussions — most questions are answered within a day.') }}</p>
                <a href="https://github.com/nylo-core/nylo/discussions" target="_blank" rel="noopener">{{ __('Open a discussion') }} ↗</a>
            </div>
        </aside>
    </div>
    </div>

    {{-- Search modal (Algolia-powered command palette) --}}
    @include('components.docs-search-modal')

    {{-- Global search keyboard shortcut --}}
    <script type="text/javascript">
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                Alpine.store('search').toggle();
            }
        });
    </script>

    {{-- highlight.js (bundled with jquery/axios); runs highlightAll() on load,
         so it colours the fences before the wrapper script below reparents them. --}}
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    {{-- Code panels: wrap each markdown fence in the design's chrome --}}
    <script type="text/javascript">
        window.nyDocsStrings = @json([
            'copy' => __('Copy'),
            'copied' => __('Copied'),
        ]);

        document.addEventListener('DOMContentLoaded', function() {
            function fallbackCopy(text) {
                const area = document.createElement('textarea');
                area.value = text;
                area.style.position = 'fixed';
                area.style.left = '-9999px';
                document.body.appendChild(area);
                area.select();
                try { document.execCommand('copy'); } finally { area.remove(); }
            }

            function copyText(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }
                fallbackCopy(text);
            }

            document.querySelectorAll('.ny-doc-prose pre').forEach(function(pre) {
                if (pre.closest('.ny-doc-code')) return;

                const code = pre.querySelector('code');
                const langClass = (code && code.className || '').match(/language-([\w+-]+)/);
                const raw = langClass ? langClass[1] : 'code';
                // `plaintext` fences are terminal output, not a language.
                const lang = (raw === 'plaintext' || raw === 'text') ? 'output' : raw;

                const shell = document.createElement('div');
                shell.className = 'ny-doc-code';

                const head = document.createElement('div');
                head.className = 'ny-doc-code-head';

                const label = document.createElement('span');
                label.className = 'ny-doc-code-lang';
                label.textContent = lang;

                const strings = window.nyDocsStrings || { copy: 'Copy', copied: 'Copied' };

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'ny-doc-code-copy';
                button.textContent = strings.copy;
                button.addEventListener('click', async function() {
                    await copyText(code ? code.textContent : pre.textContent);
                    button.textContent = strings.copied + ' ✓';
                    button.classList.add('is-copied');
                    setTimeout(function() {
                        button.textContent = strings.copy;
                        button.classList.remove('is-copied');
                    }, 1800);
                });

                head.appendChild(label);
                head.appendChild(button);

                pre.parentNode.insertBefore(shell, pre);
                shell.appendChild(head);
                shell.appendChild(pre);
            });

            // Wide markdown tables scroll inside their own box.
            document.querySelectorAll('.ny-doc-prose > table').forEach(function(table) {
                const wrap = document.createElement('div');
                wrap.className = 'ny-doc-table-scroll';
                table.parentNode.insertBefore(wrap, table);
                wrap.appendChild(table);
            });
        });
    </script>

    {{-- Section anchors --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // The docs author anchors as an empty <div id="…"> immediately before
            // the heading they name. Jumping to that div lands the heading under
            // the sticky header, so move the id onto the heading itself — it has
            // the right scroll-margin, and the scroll spy then observes the real
            // heading rather than a zero-height div.
            document.querySelectorAll('.ny-doc-prose div[id]').forEach(function(anchor) {
                if (anchor.children.length || anchor.textContent.trim()) return;

                const heading = anchor.nextElementSibling;
                if (!heading || !/^H[1-6]$/.test(heading.tagName) || heading.id) return;

                heading.id = anchor.id;
                anchor.removeAttribute('id');
            });

            // A page opened on a #hash was already scrolled to the old target
            // before this ran, so redo the jump now the id has moved.
            if (window.location.hash) {
                const target = document.getElementById(decodeURIComponent(window.location.hash.slice(1)));
                if (target) target.scrollIntoView({ behavior: 'instant', block: 'start' });
            }
        });
    </script>

    {{-- Reading progress + on-this-page scroll spy --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const tocLinks = Array.from(document.querySelectorAll('.ny-doc-toc-link[data-anchor]'));
            const readingProgress = document.getElementById('reading-progress');

            const updateProgress = function() {
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const progress = docHeight > 0 ? Math.min((window.scrollY / docHeight) * 100, 100) : 0;
                if (readingProgress) readingProgress.style.width = progress + '%';
            };

            let ticking = false;
            window.addEventListener('scroll', function() {
                if (ticking) return;
                ticking = true;
                window.requestAnimationFrame(function() {
                    updateProgress();
                    ticking = false;
                });
            });
            updateProgress();

            if (!tocLinks.length) return;

            const headings = tocLinks
                .map(function(link) {
                    const el = document.getElementById(link.dataset.anchor);
                    return el ? { el: el, link: link } : null;
                })
                .filter(Boolean);

            if (!headings.length) return;

            const setActive = function(link) {
                tocLinks.forEach(function(l) { l.classList.remove('is-active'); });
                if (link) link.classList.add('is-active');
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    const match = headings.find(function(h) { return h.el === entry.target; });
                    if (match) setActive(match.link);
                });
            }, { rootMargin: '-100px 0px -65% 0px', threshold: 0 });

            headings.forEach(function(h) { observer.observe(h.el); });
            setActive(headings[0].link);
        });
    </script>

    @yield('scripts')
</body>

</html>
