<!DOCTYPE html>
<html lang="en" class="dark [--scroll-mt:9.875rem] lg:[--scroll-mt:6.3125rem]">
<head>
    <meta name="author" content="{{ config('app.name') }}" />
    {!! SEO::generate(true) !!}

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="{{ config('project.meta.fa_integrity') }}" crossorigin="anonymous">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://unpkg.com/@highlightjs/cdn-assets@11.5.0/styles/default.min.css">

    @env('production')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('project.meta.ga_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ config('project.meta.ga_id') }}');
    </script>
    @endenv

    <meta name="viewport" content="width=device-width" />
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900" style="font-family: 'Outfit', sans-serif;">
    <div>
        <div
        class="sticky top-0 z-40 w-full backdrop-blur flex-none transition-colors duration-500 lg:z-50 lg:border-b lg:border-slate-900/10 dark:border-slate-50/[0.06] bg-white/95 supports-backdrop-blur:bg-white/60 dark:bg-transparent"
        >
        <div class="max-w-8xl mx-auto">
            <div class="py-4 border-b border-slate-900/10 lg:px-8 lg:border-0 dark:border-slate-300/10 mx-4 lg:mx-0">
                <div class="relative flex items-center">
                    <a class="mr-3 flex-none w-[2.0625rem] overflow-hidden md:w-auto" href="/">
                        <span class="sr-only">{{ config('app.name') }} home page</span>

                        <img src="{{ asset('images/nylo_logo_filled.png') }}" class="hidden md:block lg:block h-10">
                        <img src="{{ asset('images/nylo_logo.png') }}" class="block md:hidden lg:hidden">
                    </a>

                    <div class="relative">
                        <div x-data="{isOpen: false}">
                            <button
                            class="text-xs leading-5 font-semibold bg-slate-400/10 rounded-full py-1 px-3 flex items-center space-x-2 hover:bg-slate-400/20 dark:highlight-white/5"
                            id="headlessui-menu-button-undefined"
                            type="button"
                            aria-haspopup="true"
                            aria-expanded="false"
                            @click="isOpen = !isOpen" 
                            @keydown.escape="isOpen = false" 
                            >
                            v<!-- -->{{ $version }}<svg width="6" height="3" class="ml-2 overflow-visible" aria-hidden="true"><path d="M0 0L3 3L6 0" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path></svg>
                        </button>

                        <ul x-show="isOpen"
                        @click.away="isOpen = false"
                        class="absolute font-normal bg-white shadow overflow-hidden rounded w-24 border mt-2 py-1 right-0 z-20"
                        >
                        @foreach(array_keys(config('project.doc-index.versions')) as $nyloDocVersion)
                        <li>
                            <a href="{{ route('landing.docs', ['version' => $nyloDocVersion, 'page' => 'installation']) }}" class="flex items-center px-3 py-3 hover:bg-gray-200">
                                <span class="ml-2">{{ $nyloDocVersion }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="relative hidden lg:flex items-center ml-auto">
                @include('docs.nav')
            </div>
        </div>
    </div>
    <div class="flex items-center p-4 border-b border-slate-900/10 lg:hidden dark:border-slate-50/[0.06]">
        <button type="button" id="open-menu" class="text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300">
            <span class="sr-only">Navigation</span><svg width="24" height="24"><path d="M5 6h14M5 12h14M5 18h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>
        </button>
        <ol class="ml-4 flex text-sm leading-6 whitespace-nowrap min-w-0">
            <li class="flex items-center">
                {{ str($section)->headline() }}
                <svg width="3" height="6" aria-hidden="true" class="mx-3 overflow-visible text-slate-400"><path d="M0 0L3 3L0 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path></svg>
            </li>
            <li class="font-semibold text-slate-900 truncate dark:text-slate-200">{{ str($page)->headline() }}</li>
        </ol>
    </div>
</div>
</div>
<div class="overflow-hidden">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 md:px-8">
        <div id="nav-controller" class="hidden lg:block fixed z-20 inset-0 top-[3.8125rem] left-[max(0px,calc(50%-45rem))] right-auto w-[19.5rem] pb-10 px-8 overflow-y-auto">
            <nav id="nav" class="lg:text-sm lg:leading-6 relative">

                <div class="sticky top-0 -ml-0.5 pointer-events-none">
                    <div class="h-10 bg-white dark:bg-slate-900"></div>

                    <div class="h-8 bg-gradient-to-b from-white dark:from-slate-900"></div>
                </div>

                @include('docs.sidebar')

            </nav>
        </div>
        <div class="lg:pl-[19.5rem]">

            <main class="max-w-3xl mx-auto relative z-20 pt-10 xl:max-w-none">
                <div class="prose dark:prose-invert prose-blockquote:bg-slate-400/10 prose-blockquote:p-4 prose-blockquote:rounded-md prose-blockquote:shadow-sm self-center m-auto">
                    @yield('content')

                    @include('docs.footer')
                </div>
            </main>

            <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        </div>
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
</body>
</html>
