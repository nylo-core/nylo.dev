@php
    $docVersions = array_keys(config('project.doc-index.versions'));
@endphp

{{-- Version switcher --}}
<div class="relative mb-[26px]" x-data="{ open: false }" @click.away="open = false" @keydown.escape="open = false">
    <button type="button" class="ny-doc-version" @click="open = !open" :aria-expanded="open">
        <span class="flex items-center gap-2">
            <span class="ny-doc-version-dot"></span>{{ __('Version') }} {{ $version }}
        </span>
        <svg class="w-2.5 h-2.5 ny-c-faint transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute left-0 right-0 top-full mt-1.5 z-30 rounded-[10px] border ny-bd ny-bg py-1 shadow-lg">
        @foreach($docVersions as $docVersion)
        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $docVersion, 'page' => 'installation']) }}"
           class="flex items-center gap-2 px-3 py-2 text-[13.5px] {{ $docVersion === $version ? 'font-semibold ny-c-blue' : 'ny-c-strong hover:ny-c-ink' }}"
           style="border-radius:7px">
            <span class="w-1.5 h-1.5 rounded-full flex-none" style="background: {{ $docVersion === $version ? 'var(--ny-cyan)' : 'var(--ny-line)' }}"></span>
            {{ $docVersion }}
            @if($docVersion === $latestVersionOfNylo)
                <span class="ml-auto font-mono text-[10px] tracking-[0.1em] ny-c-muted uppercase">{{ __('latest') }}</span>
            @endif
        </a>
        @endforeach
    </div>
</div>

{{-- Doc sections --}}
@foreach(config('project.doc-index.versions')[$version] as $sectionKey => $docLinks)
<div class="ny-doc-section" x-data="{ open: {{ $sectionKey === $section ? 'true' : 'false' }} }">
    <button type="button" class="ny-doc-group-label" @click="open = !open" :aria-expanded="open">
        <span>{{ __(str($sectionKey)->headline()->toString()) }}</span>
        <svg class="w-2.5 h-2.5 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <div class="ny-doc-group" x-show="open" x-collapse>
        @foreach($docLinks as $docLink)
        <a class="ny-doc-link {{ $docLink === $page ? 'ny-doc-link-active' : '' }}"
           @if($docLink === $page) aria-current="page" @endif
           href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'page' => $docLink, 'version' => $version]) }}">
            @if (str($docLink)->startsWith('ny'))
                {{ str($docLink)->headline()->replace(' ', '') }}
            @else
                {{ __(str($docLink)->headline()->toString()) }}
            @endif
        </a>
        @endforeach
    </div>
</div>
@endforeach

{{-- Packages --}}
<div class="ny-doc-section" x-data="{ open: false }">
    <button type="button" class="ny-doc-group-label" @click="open = !open" :aria-expanded="open">
        <span>{{ __('Packages') }}</span>
        <svg class="w-2.5 h-2.5 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <div class="ny-doc-group" x-show="open" x-collapse>
        @foreach(config('project.packages-index') as $packageLink)
        <a target="_BLANK" class="ny-doc-link flex items-center gap-1.5"
           href="https://pub.dev/packages/{{ str($packageLink['link'])->replace('-', '_') }}">
            {{ $packageLink['label'] }}
            <span class="text-[10px] ny-c-faint" aria-hidden="true">↗</span>
        </a>
        @endforeach
    </div>
</div>
