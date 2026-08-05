<footer class="ny-bg border-t ny-bd-soft">
    <div class="ny-shell grid gap-10 pt-16 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr] lg:gap-12">
        {{-- Brand --}}
        <div>
            <div class="flex items-center gap-2.5 mb-4">
                <img src="{{ asset('images/nylo_logo.png') }}" alt="" class="ny-mark h-[26px]">
                <span class="ny-wordmark">{{ config('app.name') }}</span>
            </div>
            <p class="max-w-[280px] text-[14.5px] leading-[1.65] ny-c-body">
                {{ __('A micro-framework for Flutter, designed to simplify how you build and ship apps.') }}
            </p>
        </div>

        {{-- Documentation --}}
        <div>
            <div class="ny-footer-heading mb-[18px]">{{ __('Documentation') }}</div>
            <div class="flex flex-col gap-3">
                <a class="ny-footer-link" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'installation']) }}">{{ __('Installation') }}</a>
                <a class="ny-footer-link" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'requirements']) }}">{{ __('Requirements') }}</a>
                <a class="ny-footer-link" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'router']) }}">{{ __('Router') }}</a>
                <a class="ny-footer-link" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'themes-and-styling']) }}">{{ __('Themes & styling') }}</a>
            </div>
        </div>

        {{-- Resources --}}
        <div>
            <div class="ny-footer-heading mb-[18px]">{{ __('Resources') }}</div>
            <div class="flex flex-col gap-3">
                <a class="ny-footer-link" href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK">{{ __('Flutter packages') }}</a>
                <a class="ny-footer-link" href="https://github.com/nylo-core/nylo">{{ __('Contributions') }}</a>
                <a class="ny-footer-link" href="{{ route('landing.privacy-policy', ['locale' => app()->getLocale()]) }}">{{ __('Privacy policy') }}</a>
                <a class="ny-footer-link" href="{{ route('landing.terms-and-conditions', ['locale' => app()->getLocale()]) }}">{{ __('Terms & conditions') }}</a>
            </div>
        </div>

        {{-- Community --}}
        <div>
            <div class="ny-footer-heading mb-[18px]">{{ __('Community') }}</div>
            <div class="flex flex-col gap-3">
                <a class="ny-footer-link" href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">{{ __('Discussions') }}</a>
                <a class="ny-footer-link" href="https://github.com/nylo-core/nylo" target="_BLANK">GitHub</a>
                <a class="ny-footer-link" href="https://twitter.com/nylo_dev" target="_BLANK">Twitter</a>
                <a class="ny-footer-link" href="https://www.youtube.com/@nylo_dev" target="_BLANK">YouTube</a>
            </div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="ny-shell">
        <div class="mt-14 flex flex-wrap items-center justify-between gap-5 border-t ny-bd-soft py-6">
            <span class="text-[13.5px] ny-c-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved') }}.</span>

            <div class="flex items-center gap-5">
                {{-- Language Switcher --}}
                @if(Route::currentRouteName() && str_starts_with(Route::currentRouteName(), 'landing.'))
                    <x-language-switcher />
                @endif

                <a href="https://github.com/nylo-core/nylo" target="_BLANK" class="text-[13.5px] font-medium ny-c-body hover:opacity-70 transition-opacity">GitHub</a>
                <a href="https://twitter.com/nylo_dev" target="_BLANK" class="text-[13.5px] font-medium ny-c-body hover:opacity-70 transition-opacity">Twitter</a>
                <a href="https://www.youtube.com/@nylo_dev" target="_BLANK" class="text-[13.5px] font-medium ny-c-body hover:opacity-70 transition-opacity">YouTube</a>
            </div>
        </div>
    </div>
</footer>
