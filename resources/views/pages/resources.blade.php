@extends('layouts.app-landing')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="relative min-h-[50vh] flex items-center overflow-hidden bg-white dark:bg-slate-900 transition-colors duration-300 mt-5 rounded-3xl mx-4">
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
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="animate-fade-in-up stagger-2 text-5xl md:text-6xl lg:text-7xl font-medium mb-6 text-gray-900 dark:text-white leading-[1.1]" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                <span class="text-h1-gradient font-bold">Resources</span>
            </h1>

            <p class="animate-fade-in-up stagger-3 text-lg md:text-xl text-gray-500 dark:text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Everything you need to build with {{ config('app.name') }}. Documentation, tutorials, community, and more.
            </p>
        </div>
    </div>
</section>

{{-- ===== GETTING STARTED SECTION ===== --}}
<section class="relative py-28 bg-white dark:bg-slate-900 transition-colors duration-300 overflow-hidden">
    <div class="absolute inset-0 dot-grid-pattern opacity-30"></div>

    <div class="relative container mx-auto px-6 lg:px-32">
        <div class="text-center mb-16">
            <span class="inline-block text-primary-blue text-sm font-semibold uppercase tracking-widest mb-4">Getting Started</span>
            <h2 class="text-4xl md:text-5xl font-medium text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                Start <span class="text-h1-gradient font-bold">building</span> today
            </h2>
            <p class="text-lg text-gray-500 dark:text-slate-400 max-w-2xl mx-auto">
                Comprehensive documentation and resources to get you up and running quickly.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            {{-- Documentation Card --}}
            <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}" class="feature-card-enhanced group">
                <div class="icon-container mb-5">
                    <svg class="w-6 h-6 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-primary-blue transition-colors">Documentation</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">Comprehensive guides to help you get started with Nylo. Learn routing, state management, networking, and more.</p>
                <div class="mt-4 flex items-center text-primary-blue font-medium">
                    Read the docs
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
            </a>

            {{-- Release Notes Card --}}
            <a href="https://github.com/nylo-core/nylo/releases" target="_BLANK" class="feature-card-enhanced group">
                <div class="icon-container purple mb-5">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 transition-colors">Release Notes</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">Stay updated with the latest Nylo releases, new features, improvements, and bug fixes.</p>
                <div class="mt-4 flex items-center text-purple-600 font-medium">
                    View releases
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- Section Divider --}}
<div class="section-divider"></div>

{{-- ===== LEARN SECTION ===== --}}
<section class="relative py-28 bg-gray-50 dark:bg-slate-800/50 transition-colors duration-300 overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full dot-grid-pattern opacity-20"></div>

    <div class="relative container mx-auto px-6 lg:px-32">
        <div class="text-center mb-16">
            <span class="inline-block text-primary-blue text-sm font-semibold uppercase tracking-widest mb-4">Learn</span>
            <h2 class="text-4xl md:text-5xl font-medium text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                Tutorials & <span class="text-h1-gradient font-bold">guides</span>
            </h2>
            <p class="text-lg text-gray-500 dark:text-slate-400 max-w-2xl mx-auto">
                Video tutorials, articles, and community discussions to help you master Nylo.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- YouTube Card --}}
            <a href="https://www.youtube.com/@nylo_dev" target="_BLANK" class="feature-card-enhanced group">
                <div class="icon-container red mb-5">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-red-500 transition-colors">YouTube</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">Video tutorials and guides to help you build Flutter apps with Nylo.</p>
                <div class="mt-4 flex items-center text-red-500 font-medium">
                    Watch tutorials
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
            </a>

            {{-- Forum Card --}}
            <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK" class="feature-card-enhanced group">
                <div class="icon-container green mb-5">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 transition-colors">Forum</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">Join community discussions, ask questions, and share your knowledge with other developers.</p>
                <div class="mt-4 flex items-center text-green-600 font-medium">
                    Join discussions
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
            </a>

            {{-- GitHub Card --}}
            <a href="https://github.com/nylo-core/nylo" target="_BLANK" class="feature-card-enhanced group">
                <div class="icon-container mb-5" style="background: linear-gradient(135deg, rgba(36, 41, 47, 0.1), rgba(36, 41, 47, 0.05));">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">GitHub</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">Explore the source code, report issues, and contribute to the Nylo framework.</p>
                <div class="mt-4 flex items-center text-gray-700 dark:text-gray-300 font-medium">
                    View repository
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- Section Divider --}}
<div class="section-divider"></div>

{{-- ===== SUPPORT NYLO SECTION ===== --}}
<section class="relative py-28 bg-white dark:bg-slate-900 transition-colors duration-300 overflow-hidden">
    <div class="absolute inset-0 dot-grid-pattern opacity-30"></div>

    <div class="relative container mx-auto px-6 lg:px-32">
        <div class="text-center mb-16">
            <span class="inline-block text-primary-blue text-sm font-semibold uppercase tracking-widest mb-4">Support</span>
            <h2 class="text-4xl md:text-5xl font-medium text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                Support <span class="text-h1-gradient font-bold">Nylo</span>
            </h2>
            <p class="text-lg text-gray-500 dark:text-slate-400 max-w-2xl mx-auto">
                Help us continue developing and maintaining Nylo by becoming a sponsor.
            </p>
        </div>

        <div class="max-w-3xl mx-auto">
            <a href="https://opencollective.com/nylo" target="_BLANK" class="block group">
                <div class="relative bg-gradient-to-br from-primary-blue/5 to-purple-500/5 dark:from-primary-blue/10 dark:to-purple-500/10 rounded-2xl p-4 md:p-8 border border-gray-200 dark:border-slate-700 hover:border-primary-blue/50 dark:hover:border-primary-blue/50 transition-all duration-300 hover:shadow-xl">
                    <div class="flex flex-col md:flex-row items-center gap-8 pt-4">
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">Become a Sponsor</h3>
                            <p class="text-gray-500 dark:text-slate-400 leading-relaxed mb-4">
                                Your sponsorship helps fund the ongoing development, maintenance, and improvement of Nylo. Every contribution makes a difference.
                            </p>
                            <div class="inline-flex items-center text-primary-blue font-semibold group-hover:text-primary-blue-deep transition-colors">
                                Sponsor on OpenCollective
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- Section Divider --}}
<div class="section-divider"></div>

{{-- ===== PACKAGES SECTION ===== --}}
<section class="relative py-28 bg-gray-50 dark:bg-slate-800/50 transition-colors duration-300 overflow-hidden">
    <div class="absolute top-0 left-0 w-1/2 h-full dot-grid-pattern opacity-20"></div>

    <div class="relative container mx-auto px-6 lg:px-32">
        <div class="text-center mb-16">
            <span class="inline-block text-primary-blue text-sm font-semibold uppercase tracking-widest mb-4">Packages</span>
            <h2 class="text-4xl md:text-5xl font-medium text-gray-900 dark:text-white mb-5" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                Flutter <span class="text-h1-gradient font-bold">packages</span>
            </h2>
            <p class="text-lg text-gray-500 dark:text-slate-400 max-w-2xl mx-auto">
                Explore our collection of Flutter packages available on pub.dev to enhance your applications.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(config('project.packages-index') as $package)
            <a href="https://pub.dev/packages/{{ str($package['link'])->replace('-', '_') }}" target="_BLANK" class="feature-card-enhanced group">
                <div class="icon-container cyan mb-5">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-cyan-600 transition-colors">{{ $package['label'] }}</h3>
                <p class="text-gray-500 dark:text-slate-400 leading-relaxed">{{ $package['description'] }}</p>
                <div class="mt-4 flex items-center text-cyan-600 font-medium">
                    View on pub.dev
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>

        {{-- View All Packages Link --}}
        <div class="text-center mt-14">
            <a href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK" class="group inline-flex items-center gap-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:border-primary-blue/50 dark:hover:border-primary-blue/50 text-gray-700 dark:text-slate-300 font-medium px-6 h-[48px] rounded-xl transition-all duration-300 shadow-sm hover:shadow-lg">
                View all packages on pub.dev
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>
    </div>
</section>

@endsection
