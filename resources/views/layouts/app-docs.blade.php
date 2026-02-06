<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" x-data x-bind:class="{ 'dark': $store.darkMode.on }" class="[--scroll-mt:9.875rem] lg:[--scroll-mt:6.3125rem] scroll-smooth" style="overflow: hidden; height: 100%;">

<head>
    <script>
        (function() {
            var darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'true' || (darkMode === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <meta name="author" content="{{ config('app.name') }}" />
    {!! SEO::generate(true) !!}

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="{{ config('project.meta.fa_integrity') }}" crossorigin="anonymous">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://unpkg.com/@highlightjs/cdn-assets@11.5.0/styles/default.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    <meta name="viewport" content="width=device-width" />
    <meta charset="utf-8" />

    @if (app()->environment('production'))
        <link rel="stylesheet" href="{{ asset('css/docs.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/docs.css') }}">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@4" />
    <link rel="preconnect" href="https://CN0FWF0JLR-dsn.algolia.net" crossorigin />
</head>

<body class="antialiased text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 font-[outfit]" style="height: 100%; overflow: auto;">
    <div>
        <div
            class="sticky top-0 z-40 w-full backdrop-blur flex-none transition-colors duration-500 lg:z-50 lg:border-b lg:border-slate-900/10 dark:border-slate-50/[0.06] bg-white/95 supports-backdrop-blur:bg-white/60 dark:bg-transparent">
            <div class="max-w-8xl mx-auto">
                <div
                    class="py-4 border-b-0 sm:border-b border-slate-900/10 lg:px-8 lg:border-0 dark:border-slate-300/10 mx-4 lg:mx-0">
                    <div class="relative flex items-center">
                        <a class="mr-3 flex-none w-[2.0625rem] overflow-hidden md:w-auto" href="/">
                            <span class="sr-only">{{ config('app.name') }} home page</span>

                            <img src="{{ asset('images/nylo_logo_filled.png') }}"
                                class="hidden md:block lg:block h-10">
                            <img src="{{ asset('images/nylo_logo.png') }}" class="block md:hidden lg:hidden">
                        </a>

                        <div class="relative" style="margin-right: 82px;">
                            <div x-data="{ isOpen: false }">
                                <button
                                    class="version-switcher text-xs leading-5 font-semibold bg-slate-400/10 rounded-full py-1 px-3 flex items-center space-x-2 hover:bg-slate-400/20 dark:highlight-white/5"
                                    id="headlessui-menu-button-undefined" type="button" aria-haspopup="true"
                                    aria-expanded="false" @click="isOpen = !isOpen" @keydown.escape="isOpen = false">
                                    v{{ $version }}<svg width="6" height="3"
                                        class="ml-2 overflow-visible" aria-hidden="true">
                                        <path d="M0 0L3 3L6 0" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                    </svg>
                                </button>

                                <ul x-show="isOpen" @click.away="isOpen = false"
                                    class="absolute font-normal bg-white shadow overflow-hidden rounded w-24 border mt-2 py-1 right-0 z-20"
                                    style="display:none;">
                                    @foreach (array_keys(config('project.doc-index.versions')) as $nyloDocVersion)
                                        <li>
                                            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $nyloDocVersion, 'page' => 'installation']) }}"
                                                class="flex items-center px-3 py-3 hover:bg-gray-200">
                                                <span class="ml-2">{{ $nyloDocVersion }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="inline-flex mx-0 sm:mx-4 px-0 sm:px-6 py-2 rounded-2xl w-full cursor-pointer" id="search">

                        </div>

                        <div class="hidden items-center lg:flex relative">
                            @include('docs.nav')
                        </div>
                    </div>
                </div>
                <div class="flex items-center p-4 border-b border-slate-900/10 lg:hidden dark:border-slate-50/[0.06]">
                    <button type="button" id="open-menu"
                        class="text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300">
                        <span class="sr-only">Navigation</span><svg width="24" height="24">
                            <path d="M5 6h14M5 12h14M5 18h14" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round"></path>
                        </svg>
                    </button>
                    <ol class="ml-4 flex text-sm leading-6 whitespace-nowrap min-w-0">
                        <li class="flex items-center">
                            {{ str($section)->headline() }}
                            <svg width="3" height="6" aria-hidden="true"
                                class="mx-3 overflow-visible text-slate-400">
                                <path d="M0 0L3 3L0 6" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                            </svg>
                        </li>
                        <li class="font-semibold text-slate-900 truncate dark:text-slate-200">
                            {{ str($page)->headline() }}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="overflow-hidden">
            <div class="max-w-8xl mx-auto">
                <div class="flex gap-8">
                    <!-- Left Sidebar Navigation -->
                    <div id="nav-controller"
                        class="hidden lg:block fixed z-20 w-[19.5rem] h-[calc(100vh-3.8125rem)] pb-10 px-8 overflow-y-auto bg-gray-50 dark:bg-slate-400/10">
                        <nav id="nav" class="lg:text-sm lg:leading-6 relative">

                            <div class="sticky top-0 -ml-0.5 pointer-events-none hidden sm:block h-10">
                                <div class="h-10"></div>

                                <div class="h-8"></div>
                            </div>

                            @include('docs.sidebar')

                        </nav>
                    </div>

                    <!-- Main Content Area -->
                    <div class="lg:ml-[19.5rem] lg:mr-[17rem] flex-1 min-w-0">
                        <!-- Reading Progress Bar -->
                        <div class="fixed top-0 left-0 right-0 z-50 h-0.5 bg-transparent pointer-events-none">
                            <div id="reading-progress" class="h-full bg-gradient-to-r from-[#4f9edd] to-[#07becc] transition-all duration-150 ease-out" style="width: 0%"></div>
                        </div>

                        <main class="relative z-20 pt-12 pb-20 bg-white px-6 sm:px-8 lg:px-16 dark:bg-slate-900">

                            <div class="relative max-w-3xl mx-auto">

                                @if($viewingOldDocs)
                                <div class="bg-gradient-to-r block border-l-2 border-red-400 dark:bg-slate-700 from-gray-50 mb-4 p-3 rounded shadow-sm">
                                    <span>
                                        @if ($page == 'themes' && $version != '5.x')
                                        <b>Notice:</b> You're viewing an old version of the {{ config('app.name') }} documentation.<br>Consider upgrading your project to {{ config('app.name') }} <a class="text-blue-500" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'themes-and-styling']) }}">{{ $latestVersionOfNylo }}</a>.
                                        @else
                                        <b>Notice:</b> You're viewing an old version of the {{ config('app.name') }} documentation.<br>Consider upgrading your project to {{ config('app.name') }} <a class="text-blue-500" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => $docsContainPage ? $page : 'installation']) }}">{{ $latestVersionOfNylo }}</a>.
                                        @endif
                                    </span>
                                </div>
                                @endif

                                <!-- Section Badge with enhanced styling -->
                                <div class="not-prose mb-10">
                                    <div class="inline-flex items-center gap-2">
                                        <span class="relative inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold dark:from-blue-950/50 dark:to-cyan-950/50 text-blue-700 dark:text-blue-300 uppercase tracking-wider border border-blue-100/50 dark:border-blue-800/50">
                                            <span class="absolute -left-px top-1/2 -translate-y-1/2 w-0.5 h-3 bg-gradient-to-b from-[#4f9edd] to-[#07becc] rounded-full"></span>
                                            {{ str($section)->headline() }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Enhanced prose container -->
                                <article class="docs-content prose prose-slate dark:prose-invert
                                    prose-headings:scroll-mt-24
                                    prose-headings:font-semibold
                                    prose-headings:tracking-tight
                                    prose-h1:text-4xl
                                    prose-h1:font-bold
                                    prose-h1:bg-clip-text
                                    prose-h1:text-transparent
                                    prose-h1:bg-gradient-to-r
                                    prose-h1:from-slate-900
                                    prose-h1:to-slate-700
                                    dark:prose-h1:from-white
                                    dark:prose-h1:to-slate-300
                                    prose-h2:text-2xl
                                    prose-h2:mt-16
                                    prose-h2:mb-6
                                    prose-h2:pb-4
                                    prose-h2:border-b
                                    prose-h2:border-slate-200/80
                                    dark:prose-h2:border-slate-700/80
                                    prose-h2:relative
                                    prose-h3:text-xl
                                    prose-h3:mt-10
                                    prose-h3:mb-4
                                    prose-p:leading-relaxed
                                    prose-p:text-slate-600
                                    dark:prose-p:text-slate-300
                                    prose-li:leading-relaxed
                                    prose-li:text-slate-600
                                    dark:prose-li:text-slate-300
                                    prose-strong:text-slate-900
                                    dark:prose-strong:text-slate-200
                                    prose-strong:font-semibold
                                    prose-blockquote:relative
                                    prose-blockquote:bg-gradient-to-r
                                    prose-blockquote:from-blue-50/80
                                    prose-blockquote:to-transparent
                                    dark:prose-blockquote:from-blue-950/30
                                    dark:prose-blockquote:to-transparent
                                    prose-blockquote:border-l-2
                                    prose-blockquote:border-blue-500
                                    prose-blockquote:pl-6
                                    prose-blockquote:py-4
                                    prose-blockquote:pr-4
                                    prose-blockquote:rounded-r-xl
                                    prose-blockquote:not-italic
                                    prose-blockquote:text-slate-700
                                    dark:prose-blockquote:text-slate-300
                                    prose-blockquote:shadow-sm
                                    prose-a:text-blue-600
                                    dark:prose-a:text-white
                                    prose-a:font-medium
                                    prose-a:no-underline
                                    prose-a:border-b
                                    prose-a:border-blue-200
                                    dark:prose-a:border-[#404863]
                                    prose-a:transition-all
                                    prose-a:duration-200
                                    hover:prose-a:border-blue-500
                                    dark:hover:prose-a:border-blue-400
                                    prose-code:before:content-none
                                    prose-code:after:content-none
                                    prose-code:font-medium
                                    prose-code:text-[0.9em]
                                    prose-code:px-1.5
                                    prose-code:py-0.5
                                    prose-code:rounded-md
                                    prose-code:bg-slate-100
                                    dark:prose-code:bg-slate-800
                                    prose-code:text-slate-800
                                    dark:prose-code:text-slate-200
                                    prose-pre:bg-[#0d1117]
                                    dark:prose-pre:bg-[#0d1117]
                                    prose-pre:shadow-2xl
                                    prose-pre:shadow-slate-900/10
                                    dark:prose-pre:shadow-black/30
                                    prose-pre:ring-1
                                    prose-pre:ring-slate-900/5
                                    dark:prose-pre:ring-white/10
                                    prose-pre:rounded-xl
                                    prose-table:rounded-xl
                                    prose-table:overflow-hidden
                                    prose-table:shadow-sm
                                    prose-table:ring-1
                                    prose-table:ring-slate-200
                                    dark:prose-table:ring-slate-700
                                    prose-th:bg-slate-50
                                    dark:prose-th:bg-slate-800
                                    prose-th:text-slate-700
                                    dark:prose-th:text-slate-300
                                    prose-th:font-semibold
                                    prose-th:px-4
                                    prose-th:py-3
                                    prose-td:px-4
                                    prose-td:py-3
                                    prose-td:border-t
                                    prose-td:border-slate-100
                                    dark:prose-td:border-slate-800
                                    prose-hr:border-slate-200
                                    dark:prose-hr:border-slate-800
                                    prose-hr:my-12
                                    max-w-none">

                                    @yield('content')

                                    @include('docs.footer')
                                </article>
                            </div>
                        </main>

                        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
                    </div>

                    <!-- Right Sidebar - On This Page -->
                    <aside class="hidden lg:block fixed h-[calc(100vh-3.8125rem)] overflow-y-auto right-0 top-[3.8125rem] w-[17rem] z-40 bg-gradient-to-b from-white via-white to-slate-50/50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800/50">

                        <div class="sticky top-0 -ml-0.5 pointer-events-none hidden sm:block h-10">
                                <div class="h-10"></div>

                                <div class="h-8"></div>
                            </div>

                        <!-- Decorative top border gradient -->
                        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-700 to-transparent"></div>

                        <div class="px-6 pt-10 pb-8">
                            <!-- On This Page Header -->
                            <div class="flex items-center gap-2 mb-6">
                                <div class="flex items-center justify-center w-6 h-6 rounded-md bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-950/50 dark:to-cyan-950/50 ring-1 ring-blue-100 dark:ring-blue-800/50">
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">On This Page</h3>
                            </div>

                            <!-- TOC Navigation with progress indicator -->
                            <nav id="toc-nav" class="relative">
                                <!-- Active indicator line -->
                                <div class="absolute left-0 top-0 w-0.5 h-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div id="toc-progress" class="w-full bg-gradient-to-b from-[#4f9edd] to-[#07becc] transition-all duration-300 ease-out" style="height: 0%"></div>
                                </div>

                                <ul class="flex flex-col gap-0.5 pl-4">
                                    @php
                                        $onThisPage = $docContents['on-this-page'];

                                        function renderTocItem2($item, $depth = 0) {
                                            $paddingClass = $depth > 0 ? 'pl-4' : '';
                                            $textSize = $depth > 0 ? 'text-[13px]' : 'text-sm';
                                            echo '<li class="relative">';

                                            if ($item['anchor'] !== null) {
                                                // Clickable link item
                                                echo '<a class="toc-link group flex items-start gap-2 py-1.5 ' . $paddingClass . ' ' . $textSize . ' text-slate-500 dark:text-gray-300 hover:text-slate-900 dark:hover:text-slate-200 transition-all duration-200" href="#' . $item['anchor'] . '" data-anchor="' . $item['anchor'] . '">';
                                                echo '<span class="toc-indicator absolute left-0 top-1/2 -translate-y-1/2 -translate-x-[15px] w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700 opacity-0 scale-0 transition-all duration-200"></span>';
                                                echo '<span class="leading-relaxed">' . $item['title'] . '</span>';
                                                echo '</a>';
                                            } else {
                                                // Section header (not clickable)
                                                echo '<div class="flex items-start gap-2 py-1.5 mt-3 ' . $paddingClass . ' font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-xs">';
                                                echo '<span class="leading-relaxed">' . $item['text'] . '</span>';
                                                echo '</div>';
                                            }

                                            if (!empty($item['children'])) {
                                                echo '<ul class="flex flex-col gap-0.5 mt-0.5">';
                                                foreach ($item['children'] as $child) {
                                                    renderTocItem2($child, $depth + 1);
                                                }
                                                echo '</ul>';
                                            }
                                            echo '</li>';
                                        }

                                        foreach ($onThisPage as $item) {
                                            renderTocItem2($item);
                                        }
                                    @endphp
                                </ul>
                            </nav>

                            <!-- Quick Actions -->
                            <div class="mt-10 pt-6 border-t border-slate-100 dark:border-slate-800">
                                <div class="flex flex-col gap-2">
                                    <a href="https://github.com/nylo-core/nylo/edit/main/resources/docs/{{ $version }}/{{ $page }}.md"
                                       target="_blank"
                                       rel="noopener"
                                       class="group flex items-center gap-2 text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors duration-200">
                                        <svg class="w-4 h-4 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="group-hover:underline underline-offset-2">Edit this page</span>
                                    </a>
                                    <a href="https://github.com/nylo-core/nylo/issues/new?title=Docs%20feedback&body=Page:%20{{ urlencode(request()->fullUrl()) }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="group flex items-center gap-2 text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors duration-200">
                                        <svg class="w-4 h-4 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="group-hover:underline underline-offset-2">Report an issue</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Keyboard Shortcut Hint -->
                            <div class="mt-8 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                    <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 font-mono text-[10px] shadow-sm">⌘</kbd>
                                    <span>+</span>
                                    <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 font-mono text-[10px] shadow-sm">K</kbd>
                                    <span class="ml-1">to search</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@docsearch/js@4"></script>

    <script type="text/javascript">
        docsearch({
            appId: '{!! config('project.meta.algolia_app_id') !!}',
            apiKey: '{!! config('project.meta.algolia_app_key') !!}',
            indexName: '{!! config('project.meta.algolia_index_name') !!}',
            insights: true,
            container: '#search',
            transformItems(items) {
                return items.map((item) => {
                    var hierarchy = item.hierarchy;

                    if (item.url.indexOf("#nav")) {
                        item.url = item.url.replace(/#.*$/, '');
                    }

                    var sectionName = null;

                    if (hierarchy.lvl3 != null) {
                        sectionName = hierarchy.lvl3;
                    }

                    if (hierarchy.lvl2 != null && sectionName == null) {
                        sectionName = hierarchy.lvl2;
                    }

                    if (hierarchy.lvl4 != null && sectionName == null) {
                        sectionName = hierarchy.lvl4;
                    }

                    if (hierarchy.lvl5 != null && sectionName == null) {

                        if (["Getting Started", "Basics", "Introduction", "Advanced", "Widgets"].includes(hierarchy
                                .lvl5)) {
                            item.url = item.url.replace(/#.*$/, '');
                            return item;
                        }

                        sectionName = hierarchy.lvl5;
                    }
                    if (sectionName == null || sectionName == "") {
                        return item;
                    }

                    var sectionId = sectionName.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');

                    // remove old anchor
                    item.url = item.url.replace(/#.*$/, '');

                    item.url = (item.url + '#' + sectionId);

                    return item;
                });
            },
            recordExtractor: ({
                helpers
            }) => {
                return helpers.docsearch({
                    recordProps: {
                        lvl0: {
                            selectors: "h1",
                        },
                        lvl1: "h2",
                        lvl2: "h3",
                        content: "main p, main li",
                    },
                });
            },
            searchParameters: {
                facetFilters: ['version:latest'],
            },
            debug: false
        });
    </script>

    <script type="text/javascript">
        $(function() {
            $("#open-menu").click(function() {
                $("#nav-controller").removeClass('z-20 hidden');
                $("#nav-controller").addClass('z-40 bg-white');
                $("#close-menu").removeClass('hidden');
            });

            $("#close-menu").click(function() {
                $("#nav-controller").removeClass('z-40 bg-white');
                $("#nav-controller").addClass('z-20 hidden');
                $(this).addClass('hidden');
            });
        });
    </script>

    <!-- Enhanced Scroll Spy & Reading Progress -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const tocLinks = document.querySelectorAll('.toc-link');
            const headings = [];
            const readingProgress = document.getElementById('reading-progress');
            const tocProgress = document.getElementById('toc-progress');

            // Collect all headings that match TOC anchors
            tocLinks.forEach(link => {
                const anchor = link.getAttribute('data-anchor');
                const heading = document.getElementById(anchor);
                if (heading) {
                    headings.push({ element: heading, link: link });
                }
            });

            // Reading progress bar
            const updateReadingProgress = () => {
                const scrollTop = window.scrollY;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const progress = Math.min((scrollTop / docHeight) * 100, 100);

                if (readingProgress) {
                    readingProgress.style.width = progress + '%';
                }
                if (tocProgress) {
                    tocProgress.style.height = progress + '%';
                }
            };

            // Throttle scroll events
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        updateReadingProgress();
                        ticking = false;
                    });
                    ticking = true;
                }
            });

            // Initial progress update
            updateReadingProgress();

            if (headings.length === 0) return;

            // Active link styling with indicator animation
            const setActiveLink = (activeLink) => {
                tocLinks.forEach(link => {
                    const indicator = link.querySelector('.toc-indicator');
                    link.classList.remove('text-slate-900', 'dark:text-slate-100', 'font-medium');
                    link.classList.add('text-slate-500', 'dark:text-gray-300');
                    if (indicator) {
                        indicator.classList.remove('opacity-100', 'scale-100', 'bg-gradient-to-b', 'from-[#4f9edd]', 'to-[#07becc]');
                        indicator.classList.add('opacity-0', 'scale-0', 'bg-slate-300', 'dark:bg-slate-700');
                    }
                });
                if (activeLink) {
                    const indicator = activeLink.querySelector('.toc-indicator');
                    activeLink.classList.remove('text-slate-500', 'dark:text-gray-300');
                    activeLink.classList.add('text-slate-900', 'dark:text-slate-100', 'font-medium');
                    if (indicator) {
                        indicator.classList.remove('opacity-0', 'scale-0', 'bg-slate-300', 'dark:bg-slate-700');
                        indicator.classList.add('opacity-100', 'scale-100');
                        indicator.style.background = 'linear-gradient(to bottom, #4f9edd, #07becc)';
                    }
                }
            };

            // Intersection Observer for scroll spy
            const observerOptions = {
                root: null,
                rootMargin: '-100px 0px -65% 0px',
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const heading = headings.find(h => h.element === entry.target);
                        if (heading) {
                            setActiveLink(heading.link);
                        }
                    }
                });
            }, observerOptions);

            headings.forEach(({ element }) => {
                observer.observe(element);
            });

            // Set initial active state
            if (headings.length > 0) {
                setActiveLink(headings[0].link);
            }

            // Smooth scroll for TOC links
            tocLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const anchor = link.getAttribute('data-anchor');
                    const target = document.getElementById(anchor);
                    if (target) {
                        const offset = 100;
                        const targetPosition = target.getBoundingClientRect().top + document.body.scrollTop - offset;
                        document.body.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                        // Update URL without triggering scroll
                        history.pushState(null, '', '#' + anchor);
                    }
                });
            });
        });
    </script>

    <!-- Copy to Clipboard for Code Blocks -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const codeBlocks = document.querySelectorAll('.docs-content pre');

            const copyIcon = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.2474 6.25033V2.91699H17.0807V13.7503H13.7474M13.7474 6.25033V17.0837H2.91406V6.25033H13.7474Z" stroke="currentColor" stroke-linecap="round"></path>
            </svg>`;

            const checkIcon = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>`;

            // Fallback copy function for non-secure contexts
            function fallbackCopyText(text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
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

            async function copyToClipboard(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(text);
                    return true;
                } else {
                    return fallbackCopyText(text);
                }
            }

            codeBlocks.forEach(pre => {
                const button = document.createElement('button');
                button.className = 'copy-btn';
                button.setAttribute('aria-label', 'Copy to Clipboard');
                button.setAttribute('title', 'Copy to Clipboard');
                button.innerHTML = copyIcon;

                button.addEventListener('click', async function() {
                    const code = pre.querySelector('code')?.textContent || pre.textContent;

                    try {
                        await copyToClipboard(code);
                        button.innerHTML = checkIcon;
                        button.classList.add('copied');

                        setTimeout(() => {
                            button.innerHTML = copyIcon;
                            button.classList.remove('copied');
                        }, 2000);
                    } catch (err) {
                        console.error('Failed to copy:', err);
                    }
                });

                pre.appendChild(button);
            });
        });
    </script>
</body>

</html>