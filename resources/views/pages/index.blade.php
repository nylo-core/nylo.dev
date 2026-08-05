@extends('layouts.app-landing')

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden ny-dots">
    <div class="ny-glow-a"></div>
    <div class="ny-glow-b"></div>
    <div class="ny-fade-bottom"></div>

    <div class="relative ny-shell grid items-center gap-12 pt-14 pb-16 lg:grid-cols-[1.04fr_.96fr] lg:gap-14 lg:pt-[78px] lg:pb-[88px]">

        <div class="ny-rise min-w-0">
            {{-- Event Banner (if one is running) --}}
            @if (!empty($event))
                @if($event->isHappeningNow())
                <a href="{{ $event->link }}" target="_BLANK" class="ny-chip mb-6 gap-2.5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span><span class="font-semibold">{{ $event->title }}</span> &mdash; {{ __('Live now') }}</span>
                </a>
                @elseif($event->isUpcoming())
                <a href="{{ $event->link }}" target="_BLANK" class="ny-chip mb-6 gap-2.5">
                    <span class="font-semibold">{{ $event->title }}</span> &mdash; {{ $event->start_date->format('jS F, H:i') }} ICT
                </a>
                @endif
            @endif

            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 mb-6">
                <span class="ny-eyebrow">{{ __('Flutter micro-framework') }}</span>
                <span class="ny-sep"></span>
                <span class="ny-eyebrow-muted">{{ __('MIT licensed') }}</span>
            </div>

            <h1 class="ny-h1">
                {{ __('The Flutter') }}<br>
                <span class="ny-gradient-text">{{ __('Micro-framework') }}</span><br>
                {{ __('For Modern Apps') }}
            </h1>

            <p class="ny-lede ny-lede-lg mt-6 max-w-[494px]">
                {{ __('Nylo gives every project the same solid foundation — routing, state, networking, forms and auth — so you start at feature one instead of file one.') }}
            </p>

            {{-- Install command — after Copy, morphs into `nylo new <app>` cycling example names --}}
            <div class="mt-8 max-w-[560px]" x-data="installDemo('{{ $installCommand }}', ['beep_app', 'taxi_bot', 'homelab', 'pizza_run', 'chat_wave'])">
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="ny-eyebrow-muted">{{ __('Install in one line') }}</span>
                    <span class="h-px flex-1 bg-gradient-to-r from-[#E0E7EF] to-transparent dark:from-slate-700"></span>
                </div>

                <div class="ny-install">
                    <span class="ny-install-sigil">$</span>
                    <code><span class="whitespace-pre" x-text="base">{{ $installCommand }}</span><span class="text-[#3FC0E8]" x-text="appName"></span><span class="ny-caret ml-1.5 hidden sm:inline-block align-[-3px]"></span></code>
                    <button type="button" class="ny-install-btn" @click="copy()">
                        <span x-show="!copied">{{ __('Copy') }}</span>
                        <span x-show="copied" x-cloak class="text-[#8CE0A8]">{{ __('Copied') }} &check;</span>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-4">
                    <a href="{{ $docsUrl['installation'] }}" class="ny-arrow-link">
                        {{ __('Read the installation guide') }}
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <span class="ny-text ny-text-sm ny-c-muted">{{ __('Then follow the prompts to name your project.') }}</span>
                </div>
            </div>
        </div>

        {{-- Scaffolded project tree --}}
        <div class="ny-rise-delayed min-w-0">
            <div class="ny-frame">
                <div class="ny-frame-head">
                    <span class="ny-dot"></span>
                    <span class="ny-dot"></span>
                    <span class="ny-dot"></span>
                    <span class="ml-1.5 font-mono text-[12.5px] font-medium ny-c-body">my_app</span>
                    <span class="ml-auto rounded-[5px] bg-[#1E8FD5]/10 px-2 py-[5px] font-mono text-[10px] font-bold tracking-[0.12em] ny-c-blue">{{ __('SCAFFOLDED') }}</span>
                </div>

                <div class="ny-tree">
                    <div class="ny-tree-dir">lib/</div>
                    <div class="pl-4"><span class="ny-tree-dir">app/</span><span class="ny-tree-note">&nbsp; — {{ __('models, networking, providers') }}</span></div>
                    <div class="pl-4"><span class="ny-tree-dir">bootstrap/</span><span class="ny-tree-note">&nbsp; — {{ __('boot & setup') }}</span></div>
                    <div class="pl-4"><span class="ny-tree-dir">config/</span><span class="ny-tree-note">&nbsp; — {{ __('theme, keys, decoders') }}</span></div>
                    <div class="pl-4"><span class="ny-tree-dir">resources/</span></div>
                    <div class="pl-8"><span class="ny-tree-sub">pages/</span><span class="ny-tree-note">&nbsp; home_page.dart</span></div>
                    <div class="pl-8"><span class="ny-tree-sub">widgets/</span></div>
                    <div class="pl-8"><span class="ny-tree-sub">themes/</span></div>
                    <div class="pl-4"><span class="ny-tree-dir">routes/</span><span class="ny-tree-note">&nbsp; router.dart</span></div>
                    <div class="pl-4 ny-tree-file">main.dart</div>
                </div>

                <div class="ny-frame-foot">{{ __("Every Nylo project lands in the same shape — so does every teammate's.") }}</div>
            </div>
        </div>
    </div>

    {{-- Trust strip --}}
    <div class="relative border-t ny-bd-soft">
        <div class="ny-shell flex flex-wrap items-center gap-x-5 gap-y-2 py-5 lg:justify-between">
            <span class="ny-meta"><strong>{{ $githubStars }}</strong> &starf; {{ __('on GitHub') }}</span>
            <span class="ny-sep hidden sm:block"></span>
            <span class="ny-meta">{{ __('Dart 3 compatible') }}</span>
            <span class="ny-sep hidden sm:block"></span>
            <span class="ny-meta">{{ __('MIT licensed') }}</span>
            <span class="ny-sep hidden sm:block"></span>
            <span class="ny-meta">{{ __('Published by nylo.dev on pub.dev') }}</span>
            <span class="ny-sep hidden sm:block"></span>
            <span class="ny-meta">{{ __('Actively maintained since 2021') }}</span>
        </div>
    </div>
</section>

{{-- ===== FEATURES ===== --}}
<section class="ny-section-alt">
    <div class="ny-shell py-20 lg:py-24">
        <div class="grid items-end gap-8 mb-12 lg:grid-cols-2 lg:gap-14">
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="ny-rule"></span>
                    <span class="ny-eyebrow">{{ __('Features') }}</span>
                </div>
                <h2 class="ny-h2">{{ __('Everything you need to build') }}</h2>
            </div>
            <p class="ny-lede lg:mb-1.5">
                {{ __("Six pillars, one convention. Each ships with Metro generators, sensible defaults and an escape hatch — it's still plain Flutter underneath.") }}
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($pillars as $pillar)
            <a href="{{ $pillar['url'] }}" class="ny-card block px-6 pt-7 pb-[30px]">
                <div class="flex items-center justify-between mb-[22px]">
                    <span class="ny-num">{{ $pillar['num'] }}</span>
                    <span class="ny-mono-tag">{{ $pillar['tag'] }}</span>
                </div>
                <h3 class="ny-h4 mb-2.5">{{ $pillar['title'] }}</h3>
                <p class="ny-text">{{ $pillar['copy'] }}</p>
            </a>
            @endforeach

            {{-- Metro gets the dark treatment --}}
            <a href="{{ $docsUrl['metro'] }}" class="ny-card-dark ny-on-dark block px-6 pt-7 pb-[30px]">
                <div class="flex items-center justify-between mb-[22px]">
                    <span class="ny-num ny-num-dark">06</span>
                    <span class="ny-mono-tag">metro</span>
                </div>
                <h3 class="ny-h4 mb-2.5">{{ __('Metro CLI') }}</h3>
                <p class="ny-text">{{ __('Generate pages, models, controllers, forms and themes from one command.') }}</p>
            </a>
        </div>

        <div class="mt-9 pt-8 border-t ny-bd flex flex-wrap items-center gap-3">
            <span class="ny-eyebrow-muted mr-1.5">{{ __('Also included') }}</span>
            @foreach($extraFeatures as $feature)
            <a href="{{ $feature['url'] }}" class="ny-chip">{{ $feature['label'] }}</a>
            @endforeach
            <a href="{{ $docsUrl['index'] }}" class="ny-text-link px-1 py-2">{{ __('Explore all features') }} →</a>
        </div>
    </div>
</section>

{{-- ===== METRO CLI ===== --}}
<section class="ny-bg">
    <div class="ny-shell grid items-center gap-12 pt-20 lg:grid-cols-[.88fr_1.12fr] lg:gap-16 lg:pt-24">
        <div class="min-w-0">
            <div class="ny-pill-label mb-[22px]">
                <span class="ny-c-blue">&gt;_</span>
                <span>{{ __('Metro CLI') }}</span>
            </div>
            <h2 class="ny-h3">{{ __('Create anything from the terminal') }}</h2>
            <p class="ny-lede mt-5">
                {{ __('Metro scaffolds pages, models, controllers, widgets and more — wired into your router and folder structure, not dumped in a corner.') }}
            </p>

            <div class="mt-7 flex flex-col gap-3">
                @foreach($metroHighlights as $point)
                <div class="flex items-baseline gap-3">
                    <span class="font-mono text-[11px] font-bold leading-[1.6] text-[#3FC0E8]">→</span>
                    <span class="ny-text ny-c-strong">{{ $point }}</span>
                </div>
                @endforeach
            </div>

            <a href="{{ $docsUrl['metro'] }}" class="ny-arrow-link mt-7">
                {{ __('Learn more about Metro') }}
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="ny-terminal min-w-0">
            <div class="ny-terminal-head">
                <span class="ny-dot"></span>
                <span class="ny-dot"></span>
                <span class="ny-dot"></span>
                <span class="ml-1.5">zsh — my_app</span>
            </div>
            <div class="ny-terminal-body">
                <div><span class="prompt">$ </span>metro make:page HomePage</div>
                <div class="result">&nbsp; &check; Created lib/resources/pages/home_page.dart</div>
                <div class="h-3"></div>
                <div><span class="prompt">$ </span>metro make:api_service User</div>
                <div class="result">&nbsp; &check; Created lib/app/networking/user_api_service.dart</div>
                <div class="h-3"></div>
                <div><span class="prompt">$ </span>metro make:model User</div>
                <div class="result">&nbsp; &check; Created lib/app/models/user.dart</div>
                <div class="h-3"></div>
                <div><span class="prompt">$ </span>metro make:stateful_widget FavouriteWidget<span class="ny-caret ml-1.5 inline-block h-[15px] align-[-2px]"></span></div>
            </div>
        </div>
    </div>

    {{-- ===== NETWORKING ===== --}}
    <div class="ny-shell grid items-center gap-12 py-20 lg:grid-cols-[1.12fr_.88fr] lg:gap-16 lg:py-24">
        <div class="order-2 min-w-0 lg:order-1">
            <x-code-highlighter language="dart" header="true" title="app/networking/api_service.dart">
class ApiService extends NyApiService {
  @override
  String get baseUrl => "https://api.example.com/v1";

  Future<List<Post>> posts() async {
    return await network(
      request: (request) => request.get("/posts"),
    );
  }
}

// Usage in your page
final posts = await api<ApiService>((request) => request.posts());
            </x-code-highlighter>
        </div>

        <div class="order-1 min-w-0 lg:order-2">
            <div class="ny-pill-label mb-[22px]">
                <span class="ny-c-blue">⇄</span>
                <span>{{ __('Networking') }}</span>
            </div>
            <h2 class="ny-h3">{{ __('Effortless API integration') }}</h2>
            <p class="ny-lede mt-5">
                {{ __('Write clean, maintainable API services with automatic JSON parsing, error handling and request interceptors — no Dio boilerplate in your pages.') }}
            </p>
            <a href="{{ $docsUrl['networking'] }}" class="ny-arrow-link mt-7">
                {{ __('Learn more about Networking') }}
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ===== CODE EXPLORER ===== --}}
<section class="ny-section-alt">
    <div class="ny-shell py-20 lg:py-[92px]">
        <div class="flex items-center gap-2.5 mb-4">
            <span class="ny-rule"></span>
            <span class="ny-eyebrow">{{ __('Explore') }}</span>
        </div>
        <h2 class="ny-h2 mb-11 max-w-[640px]">{{ __("See it in the code you'd actually write") }}</h2>

        <div x-data="{ currentTab: 'routing' }" class="grid items-start gap-7 lg:grid-cols-[216px_1fr]">
            {{-- Tab rail --}}
            <div class="flex gap-1 overflow-x-auto pb-2 lg:sticky lg:top-[86px] lg:flex-col lg:overflow-visible lg:pb-0">
                @foreach($explorerTabs as $key => $label)
                <button type="button"
                        @click="currentTab = '{{ $key }}'"
                        :class="currentTab === '{{ $key }}' ? 'ny-tab-active' : ''"
                        class="ny-tab w-auto flex-none lg:w-full">
                    <span class="ny-tab-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="whitespace-nowrap">{{ $label }}</span>
                </button>
                @endforeach
                <a href="{{ $docsUrl['index'] }}" class="ny-text-link mt-3 hidden px-4 py-3 lg:block">{{ __('All modules') }} →</a>
            </div>

            {{-- Panels --}}
            <div class="min-w-0">
                <div x-show="currentTab === 'routing'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-router :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'authentication'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-authentication :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'forms'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-forms :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'state-management'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-state-management :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'events'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-events :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'scheduler'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-scheduler :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'networking'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-networking :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'storage'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-storage :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'localization'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-localization :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
                <div x-show="currentTab === 'navigation-hub'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-overview-navigation-hub :latestVersionOfNylo="$latestVersionOfNylo" />
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== COMMUNITY ===== --}}
<section class="ny-bg">
    <div class="ny-shell py-20 lg:py-24">
        <div class="grid items-end gap-8 mb-11 lg:grid-cols-2 lg:gap-14">
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="ny-rule"></span>
                    <span class="ny-eyebrow">{{ __('Community') }}</span>
                </div>
                <h2 class="ny-h2">{{ __('Built in the open, used in production') }}</h2>
            </div>
            <div class="flex flex-wrap gap-3 lg:justify-end">
                <a href="https://github.com/nylo-core/nylo" target="_BLANK" class="ny-btn-solid">
                    {{ __('Star on GitHub') }} <span class="opacity-60">{{ $githubStars }}</span>
                </a>
                <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK" class="ny-btn-ghost">
                    {{ __('Join the discussion') }} ↗
                </a>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-[1.28fr_1fr]">
            <div class="ny-quote-feature ny-on-dark flex flex-col justify-between p-8 lg:p-10">
                <p class="ny-quote">&ldquo;{{ $testimonials['featured']['quote'] }}&rdquo;</p>
                <div class="mt-8 flex items-center gap-3.5">
                    <span class="ny-avatar">{{ substr($testimonials['featured']['author'], 0, 1) }}</span>
                    <div>
                        <div class="ny-quote-name">{{ $testimonials['featured']['author'] }}</div>
                        <div class="ny-quote-role">{{ $testimonials['featured']['role'] }}</div>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1 lg:grid-rows-2">
                @foreach($testimonials['highlights'] as $item)
                <blockquote class="ny-card flex flex-col justify-between px-7 py-6">
                    <p class="ny-text ny-text-lg">&ldquo;{{ $item['quote'] }}&rdquo;</p>
                    <div class="ny-attribution mt-5">{{ $item['author'] }}</div>
                </blockquote>
                @endforeach
            </div>
        </div>

        <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($testimonials['rest'] as $item)
            <blockquote class="ny-card-quiet px-7 py-6">
                <p class="ny-text ny-text-md mb-[18px]">&ldquo;{{ $item['quote'] }}&rdquo;</p>
                <div class="ny-attribution">{{ $item['author'] }}</div>
            </blockquote>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CLOSING CTA ===== --}}
<section class="ny-band-dark ny-on-dark relative overflow-hidden">
    <div class="ny-cta-glow"></div>
    <div class="relative ny-shell flex flex-col items-center py-24 text-center lg:py-[104px]" x-data="{ copied: false }">
        <img src="{{ asset('images/nylo_logo.png') }}" alt="" class="ny-mark h-[52px] mb-8 drop-shadow-[0_8px_22px_rgba(63,192,232,.45)]">

        <h2 class="ny-h2 max-w-[760px]">{{ __('Your next Flutter app starts with one command') }}</h2>
        <p class="ny-lede ny-lede-cta mt-5 max-w-[520px]">{{ __("Free, MIT licensed, and it's still just Flutter underneath.") }}</p>

        <div class="ny-install ny-install-ghost mt-9">
            <span class="ny-install-sigil">$</span>
            <code>{{ $installCommand }}</code>
            <button type="button" class="ny-install-btn"
                    @click="nyCopyText('{{ $installCommand }}'); copied = true; setTimeout(() => copied = false, 1800)">
                <span x-show="!copied">{{ __('Copy') }}</span>
                <span x-show="copied" x-cloak class="text-[#0B5FA8]">{{ __('Copied') }} &check;</span>
            </button>
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-center gap-x-6 gap-y-3">
            <a href="{{ $docsUrl['installation'] }}" class="ny-arrow-link">
                {{ __('Read the docs') }}
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK" class="text-[15px] font-medium text-[#7E93AB] hover:text-white transition-colors">{{ __('Browse packages') }} ↗</a>
        </div>
    </div>
</section>

@endsection
