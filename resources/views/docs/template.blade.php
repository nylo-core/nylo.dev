@extends('layouts.app-docs')

@section('content')

{{-- Breadcrumbs --}}
<nav class="ny-doc-crumbs" aria-label="{{ __('Breadcrumb') }}">
    <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $version, 'page' => 'installation']) }}">{{ __('Docs') }}</a>
    <span class="ny-doc-crumbs-sep" aria-hidden="true">/</span>
    <span>{{ __(str($section)->headline()->toString()) }}</span>
    <span class="ny-doc-crumbs-sep" aria-hidden="true">/</span>
    <span class="ny-doc-crumbs-current">{{ $docContents['title'] }}</span>
</nav>

{{-- Title + page actions --}}
<div class="flex flex-wrap items-start justify-between gap-x-6 gap-y-4">
    <h1 class="ny-doc-title">{{ $docContents['title'] }}</h1>

    <div class="flex flex-wrap gap-2 min-w-0 pt-1.5" x-data="{ copied: false }">
        <button type="button" class="ny-doc-action"
                title="{{ __('Copy this page as Markdown') }}"
                @click="
                    const markdown = $refs.rawMarkdown.value;
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(markdown);
                    } else {
                        $refs.rawMarkdown.select();
                        document.execCommand('copy');
                    }
                    copied = true;
                    setTimeout(() => copied = false, 1800);
                ">
            <span x-show="!copied">{{ __('Copy as Markdown') }}</span>
            <span x-show="copied" x-cloak class="text-[#0B7A4B] dark:text-[#7ED9A8]">{{ __('Copied') }} &check;</span>
        </button>
        <textarea x-ref="rawMarkdown" class="sr-only" aria-hidden="true" tabindex="-1" readonly>{{ $docContents['rawMarkdown'] }}</textarea>

        {{-- Docs markdown lives in this repo (nylo.dev), not the framework repo. --}}
        <a href="https://github.com/nylo-core/nylo.dev/edit/master/resources/docs/{{ $version }}/{{ app()->getLocale() }}/{{ $page }}.md"
           target="_blank" rel="noopener" class="ny-doc-action">{{ __('Edit on GitHub') }} ↗</a>
    </div>
</div>

{{-- Mobile table of contents --}}
@if(!empty($docContents['on-this-page']))
<details class="xl:hidden mt-7 rounded-xl border ny-bd ny-bg-raised px-5 py-4">
    <summary class="cursor-pointer font-mono text-[10.5px] font-bold uppercase tracking-[0.15em] ny-c-muted">{{ __('On this page') }}</summary>
    <div class="mt-3 flex flex-col gap-1">
        @foreach($docContents['on-this-page'] as $item)
            @if($item['anchor'])
                <a href="#{{ $item['anchor'] }}" class="ny-doc-toc-link">{{ $item['text'] }}</a>
            @else
                <span class="ny-doc-toc-link">{{ $item['text'] }}</span>
            @endif
            @foreach($item['children'] ?? [] as $child)
                @if($child['anchor'])
                    <a href="#{{ $child['anchor'] }}" class="ny-doc-toc-link ny-doc-toc-sub">{{ $child['text'] }}</a>
                @endif
            @endforeach
        @endforeach
    </div>
</details>
@endif

<article class="ny-doc-prose mt-8">
    {!! str($docContents['contents'])->markdown() !!}
</article>

@endsection
