@extends('layouts.app-landing')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden bg-white dark:bg-slate-900 transition-colors duration-300 mt-5 rounded-3xl mx-4">
    {{-- Background Layers --}}
    <div class="absolute inset-0"></div>
    <div class="absolute inset-0 dot-grid-pattern opacity-50"></div>

    {{-- Animated gradient orbs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
    </div>

    {{-- Geometric Accents --}}
    <div class="geometric-accent top-20 right-[10%] opacity-30"></div>
    <div class="geometric-accent bottom-20 left-[5%] opacity-20" style="width: 150px; height: 150px;"></div>

    <div class="relative container mx-auto px-6 lg:px-32 pt-20 pb-24">
        {{-- Event Banner (if exists) --}}
        @if (!empty($event))
        <div class="text-center mb-10 animate-fade-in-up">
            @if($event->isHappeningNow())
            <a href="{{ $event->link }}" target="_BLANK" class="floating-badge hover:scale-105 transition-transform">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-gray-700 dark:text-slate-300"><span class="text-primary-blue font-semibold">{{ $event->title }}</span> &mdash; Live now</span>
            </a>
            @elseif($event->isUpcoming())
            <a href="{{ $event->link }}" target="_BLANK" class="floating-badge hover:scale-105 transition-transform">
                <span class="text-gray-700 dark:text-slate-300"><span class="text-primary-blue font-semibold">{{ $event->title }}</span> &mdash; {{ $event->start_date->format('jS F, H:i') }} ICT</span>
            </a>
            @endif
        </div>
        @endif

        {{-- Main Hero Content --}}
        <div class="text-center max-w-4xl mx-auto">

            <h1 class="animate-fade-in-up stagger-2 text-5xl md:text-6xl lg:text-7xl font-medium mb-6 text-gray-900 dark:text-white leading-[1.1]" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                {{ __('The Flutter') }}<br>
                <span class="text-h1-gradient font-bold">{{ __('Micro-framework') }}</span><br>
                <span class="text-gray-600 dark:text-slate-400 text-4xl md:text-5xl lg:text-6xl font-normal">{{ __('For Modern Apps') }}</span>
            </h1>

            <p class="animate-fade-in-up stagger-3 text-lg md:text-xl text-gray-500 dark:text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ __('A solid foundation for building Flutter apps. Routing, state management, networking, and more — all in one elegant package.') }}
            </p>

            {{-- CTA Buttons --}}
            <div class="animate-fade-in-up stagger-4 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 mb-16 px-4 sm:px-0">
                <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}" class="bg-neutral-700 btn-shine duration-300 font-medium gap-2 h-[48px] hover:from-primary-blue hover:scale-[1.02] hover:shadow-lg hover:to-primary-blue-deep inline-flex items-center px-8 rounded-xl shadow-lg text-white to-primary-blue transition-all dark:from-primary-blue dark:bg-slate-500 w-full sm:w-auto justify-center">
                    {{ __('Get Started') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <button type="button" onclick="copyCommand(this)" class="group w-full sm:w-auto" data-command="dart pub global activate nylo">
                    <div class="flex flex-col items-center sm:items-start rounded-lg transition-colors">
                        <div class="text-sm mb-1 text-gray-500">{{ __('Get started: Nylo installer') }}</div>
                        <div class="bg-gradient-to-r border-l border-neutral-300 border-r flex flex-col from-gray-50 items-center rounded-lg transition-colors w-full sm:w-auto dark:from-slate-700 dark:border-slate-600">
                            <div class="dark:bg-slate-700/50 flex font-medium gap-2 hover:bg-gray-50 items-center px-3 py-2 rounded-lg justify-center sm:justify-start">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#54a9d6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19h8M4 17l6-6l-6-6"></path></svg>
                                </div>
                                <div class="text-xs sm:text-sm font-mono text-gray-700 dark:text-gray-300 select-none relative flex items-center group">
                                    dart pub global activate nylo</div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-600">
                                    <svg class="copy-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.2474 6.25033V2.91699H17.0807V13.7503H13.7474M13.7474 6.25033V17.0837H2.91406V6.25033H13.7474Z" stroke="currentColor" stroke-linecap="round"></path>
                                    </svg>
                                    <svg class="check-icon hidden" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="copy-instructions hidden text-center sm:text-left mt-3">
                            <span>Run: </span><span class="font-medium text-gray-500">dart pub global activate nylo</span><span> in your terminal.</span>
                            <div class="block">
                                <span>Next: Run </span><span class="font-medium text-blue-500">nylo new example_project</span>
                            </div>
                        </div>
                    </div>
                </button>

            </div>

            {{-- Scroll Indicator --}}
            <div class="animate-fade-in-up stagger-5">
                <div class="animate-scroll-bounce inline-flex flex-col items-center text-gray-400 dark:text-slate-500">
                    <span class="text-xs uppercase tracking-widest mb-2">{{ __('Explore') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURES GRID ===== --}}
<section class="relative py-28 bg-white dark:bg-slate-900 transition-colors duration-300 overflow-hidden">
    {{-- Subtle background pattern --}}
    <div class="absolute inset-0 dot-grid-pattern opacity-30"></div>

    <div class="relative container mx-auto px-6 lg:px-32">
        <div class="text-center mb-20">
            <span class="inline-block text-primary-blue text-sm font-semibold uppercase tracking-widest mb-4">{{ __('Features') }}</span>
            <h2 class="text-4xl md:text-5xl font-medium text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                {{ __('Everything you need to build') }} <span class="text-h1-gradient font-bold"></span>
            </h2>
            <p class="text-lg text-gray-500 dark:text-slate-400 max-w-2xl mx-auto">
                {{ __('Nylo provides all the tools you need to create production-ready Flutter applications with confidence.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Feature Card: Routing --}}
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'router']) }}" class="feature-card-enhanced group">
                <div class="icr mb-5">
                    <svg class="w-6 h-6 text-primary-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-primary-blue transition-colors">{{ __('Routing') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">{{ __('Simple, declarative routing with route guards, parameters, and deep linking support.') }}</p>
            </a>

            {{-- Feature Card: State Management --}}
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'state-management']) }}" class="feature-card-enhanced group">
                <div class="purple mb-5">
                    <svg class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 transition-colors">{{ __('State Management') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">{{ __('Built-in reactive state management with controllers and easy state persistence.') }}</p>
            </a>

            {{-- Feature Card: Networking --}}
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'networking']) }}" class="feature-card-enhanced group">
                <div class="green mb-5">
                    <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 transition-colors">{{ __('Networking') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">{{ __('Elegant API service classes with automatic model serialization and interceptors.') }}</p>
            </a>

            {{-- Feature Card: Forms --}}
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'forms']) }}" class="feature-card-enhanced group">
                <div class="orange mb-5">
                    <svg class="w-6 h-6 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-orange-600 transition-colors">{{ __('Forms') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">{{ __('Powerful form handling with validation, casting, and automatic data binding.') }}</p>
            </a>

            {{-- Feature Card: Authentication --}}
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'authentication']) }}" class="feature-card-enhanced group">
                <div class="red mb-5">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-red-500 transition-colors">{{ __('Authentication') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">{{ __('Secure authentication with route guards, token storage, and session management.') }}</p>
            </a>

            {{-- Feature Card: Metro CLI --}}
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'metro']) }}" class="feature-card-enhanced group">
                <div class="cyan mb-5">
                    <svg class="w-6 h-6 text-cyan-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-cyan-600 transition-colors">{{ __('Metro CLI') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">{{ __('Generate pages, models, controllers, and more with powerful CLI commands.') }}</p>
            </a>
        </div>

        {{-- View All Features Link --}}
        <div class="text-center mt-14">
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo]) }}" class="hover-arrow text-primary-blue hover:text-primary-blue-deep font-medium transition-colors">
                {{ __('Explore all features') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- Section Divider --}}
<div class="section-divider"></div>

{{-- ===== CODE SHOWCASE SECTION ===== --}}
<section class="relative py-28 bg-gray-50 dark:bg-slate-800/50 transition-colors duration-300 overflow-hidden">
    {{-- Background decoration --}}
    <div class="absolute top-0 right-0 w-1/2 h-full dot-grid-pattern opacity-20"></div>

    <div class="relative container mx-auto px-6 lg:px-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            {{-- Left: Metro CLI --}}
            <div>
                <div class="inline-flex items-center gap-2 mb-6">
                    <span class="relative inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-gradient-to-r dark:from-blue-950/50 dark:to-cyan-950/50 dark:text-blue-300 uppercase tracking-wider border border-gray-200 dark:border-blue-800/50">
                        <span class="absolute -left-px top-1/2 -translate-y-1/2 w-0.5 h-3 bg-black rounded-full dark:bg-white"></span>
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ __('Metro CLI') }}</span>
                    </span>
                </div>

                <h3 class="text-3xl md:text-4xl font-semibold text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                    {{ __('Create anything from the terminal') }}
                </h3>
                <p class="text-lg text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                    {{ __("Metro is Nylo's CLI tool that helps you scaffold pages, models, controllers, widgets, and more with a single command.") }}
                </p>
                <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'metro']) }}" class="hover-arrow hover:text-primary-blue-deep font-medium transition-colors dark:text-white">
                    {{ __('Learn more about Metro') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            <div class="animate-float-subtle">
                <x-code-highlighter language="bash" header="false" title="Terminal" class="shadow-2xl">
metro make:page HomePage
# Creates a new page called HomePage

metro make:api_service User
# Creates a new API Service called UserApiService

metro make:model User
# Creates a new model called User

metro make:stateful_widget FavouriteWidget
# Creates a new stateful widget called FavouriteWidget
                </x-code-highlighter>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mt-32">
            {{-- Right: API Networking (reversed order) --}}
            <div class="order-2 lg:order-1 animate-float-subtle" style="animation-delay: 0.5s;">
                <x-code-highlighter language="dart" header="false" title="app/networking/api_service.dart" class="shadow-2xl">
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
            <div class="order-1 lg:order-2">
        
                <div class="inline-flex items-center gap-2 mb-6">
                    <span class="relative inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-gradient-to-r dark:from-blue-950/50 dark:to-cyan-950/50 dark:text-blue-300 uppercase tracking-wider border border-gray-200 dark:border-blue-800/50">
                        <span class="absolute -left-px top-1/2 -translate-y-1/2 w-0.5 h-3 bg-black rounded-full dark:bg-white"></span>

                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>

                        <span>{{ __('Networking') }}</span>
                    </span>
                </div>

                <h3 class="text-3xl md:text-4xl font-semibold text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                    {{ __('Effortless API integration') }}
                </h3>
                <p class="text-lg text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                    {{ __('Write clean, maintainable API services with automatic JSON parsing, error handling, and request interceptors.') }}
                </p>
                <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'networking']) }}" class="hover-arrow hover:text-primary-blue-deep font-medium transition-colors dark:text-white">
                    {{ __('Learn more about Networking') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== INTERACTIVE FEATURES TABS ===== --}}
<section class="relative py-28 bg-white dark:bg-slate-900 transition-colors duration-300">
    <div class="container mx-auto px-6 lg:px-32">
        <div class="text-center mb-14">
            <span class="inline-block text-primary-blue text-sm font-semibold uppercase tracking-widest mb-4">{{ __('Explore') }}</span>
            <h2 class="text-4xl md:text-5xl font-medium text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                {{ __('Powerful tools for creating') }}
            </h2>
            <p class="text-lg text-gray-500 dark:text-slate-400">{{ __('Everything you need to build your next Flutter app') }}</p>
        </div>

        <div x-data="{ currentTab: 'routing' }" class="max-w-6xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Tab Sidebar --}}
                <div class="lg:w-48 flex-shrink-0">
                    <div class="flex flex-row lg:flex-col flex-wrap lg:flex-nowrap gap-2">
                        @php

                        $tabs = [
                            'routing' => ['label' => __('Routing'), 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
                            'authentication' => ['label' => __('Auth'), 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                            'forms' => ['label' => __('Forms'), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            'state-management' => ['label' => __('State'), 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                            'events' => ['label' => __('Events'), 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                            'scheduler' => ['label' => __('Scheduler'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'networking' => ['label' => __('Networking'), 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                            'storage' => ['label' => __('Storage'), 'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4'],
                            'localization' => ['label' => __('Localization'), 'icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
                            'navigation-hub' => ['label' => __('Nav Hub'), 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ];
                        @endphp

                        @foreach($tabs as $key => $tab)
                        <button
                            @click="currentTab = '{{ $key }}'"
                            :class="{
                                'tab-btn-active text-white': currentTab === '{{ $key }}',
                                'text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white': currentTab !== '{{ $key }}'
                            }"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium transition-all duration-300 w-full text-left"
                        >
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                            </svg>
                            <span>{{ $tab['label'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tab Content --}}
                <div class="flex-1 bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-8 border border-gray-100 dark:border-slate-700/50">
                    <div x-show="currentTab === 'routing'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-router :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'authentication'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-authentication :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'forms'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-forms :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'state-management'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-state-management :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'events'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-events :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'scheduler'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-scheduler :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'networking'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-networking :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'storage'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-storage :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'localization'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-localization :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                    <div x-show="currentTab === 'navigation-hub'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <x-overview-navigation-hub :latestVersionOfNylo="$latestVersionOfNylo" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Section Divider --}}
<div class="section-divider"></div>

{{-- ===== COMMUNITY / TESTIMONIALS SECTION ===== --}}
<section class="relative py-28 bg-gradient-to-b from-gray-50 to-white dark:from-slate-800/50 dark:to-slate-900 transition-colors duration-300 overflow-hidden">
    {{-- Background decoration --}}
    <div class="absolute inset-0 dot-grid-pattern opacity-20"></div>

    <div class="relative container mx-auto px-6 lg:px-32">
        <div class="text-center mb-16">

            <h2 class="text-4xl md:text-5xl font-medium text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                {{ __('Loved by the community') }}
            </h2>
            <p class="text-lg text-gray-500 dark:text-slate-400 mb-10">
                {{ __('What developers are saying about') }} {{ config('app.name') }}
            </p>
            <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK" class="group inline-flex items-center gap-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:border-primary-blue/50 dark:hover:border-primary-blue/50 text-gray-700 dark:text-slate-300 font-medium px-6 h-[48px] rounded-xl transition-all duration-300 shadow-sm hover:shadow-lg">
                {{ __('Join the discussion') }}
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>

        {{-- Testimonials Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $testimonials = [
                ['quote' => "I'm new to Dart and new to your framework (which I love)", 'author' => 'Peter', 'role' => 'Senior Director of Heroku Global'],
                ['quote' => "I wanted to thank you guys for the great job you are doing.", 'author' => '@youssefKadaouiAbbassi', 'role' => null],
                ['quote' => "Just to say that I am in love with @nylo_dev's website!! Definitely gonna explore it!", 'author' => '@esfoliante_txt', 'role' => null],
                ['quote' => "Really love the concept of this framework", 'author' => '@Chrisvidal', 'role' => null],
                ['quote' => "Nylo is the best framework for flutter, which makes developing easy", 'author' => '@higakijin', 'role' => null],
                ['quote' => "This is incredible. Very well done!", 'author' => 'FireflyDaniel', 'role' => null],
                ['quote' => "Very nice Framework! Thank you so much!", 'author' => '@ChaoChao2509', 'role' => null],
                ['quote' => "I just discovered this framework and I'm very impressed. Thank you", 'author' => '@lepresk', 'role' => null],
                ['quote' => "Great work on Nylo", 'author' => '@dylandamsma', 'role' => null],
                ['quote' => 'This is by far the best framework out there. Amazing quality and features. Thanks so much.', 'author' => '@2kulfi', 'role' => null],
                ['quote' => 'It\'s interesting and very amazing. It makes the work more easier and less time consuming. Great work. Thank you', 'author' => 'darkreader01', 'role' => null],
                ['quote' => 'Salut. Je viens juste de découvrir votre outils et je le trouve vraiment super. Une belle découverte pour moi 👌🤌', 'author' => 'ojean-01', 'role' => null],

            ];
            @endphp

            @foreach($testimonials as $index => $testimonial)
            <blockquote class="testimonial-card flex flex-col justify-between {{ $index === 0 ? 'md:col-span-2 lg:col-span-1' : '' }}">
                <div class="section-divider"></div>
                <div class="p-4">
                    <p class="text-gray-700 dark:text-slate-300 mb-5 leading-relaxed relative z-10 pt-4">{{ $testimonial['quote'] }}</p>
                    <div class="flex items-center gap-3">
                        <div>
                            <span class="block font-semibold text-gray-900 dark:text-white">{{ $testimonial['author'] }}</span>
                            @if($testimonial['role'])
                            <span class="block text-sm text-gray-500 dark:text-slate-500">{{ $testimonial['role'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </blockquote>
            @endforeach
        </div>
    </div>
</section>



@endsection
