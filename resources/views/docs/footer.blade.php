@php
    $docIndex = config('project.doc-index.versions')[$version] ?? [];
    $allPages = [];
    foreach ($docIndex as $sectionKey => $pages) {
        foreach ($pages as $pageSlug) {
            $allPages[] = ['section' => $sectionKey, 'page' => $pageSlug];
        }
    }

    $currentIndex = null;
    foreach ($allPages as $index => $item) {
        if ($item['page'] === $page) {
            $currentIndex = $index;
            break;
        }
    }

    $prevPage = $currentIndex !== null && $currentIndex > 0 ? $allPages[$currentIndex - 1] : null;
    $nextPage = $currentIndex !== null && $currentIndex < count($allPages) - 1 ? $allPages[$currentIndex + 1] : null;

    $pageTitle = fn ($slug) => str($slug)->startsWith('ny')
        ? str($slug)->headline()->replace(' ', '')
        : __(str($slug)->headline()->toString());
@endphp

<footer class="not-prose">

    {{-- Was this helpful? --}}
    <div class="ny-doc-feedback" x-data="{ sent: null }">
        <span class="ny-doc-feedback-label" x-show="sent === null">{{ __('Was this page helpful?') }}</span>
        <span class="ny-doc-feedback-label" x-show="sent === 'yes'" x-cloak>{{ __('Glad it helped — thanks for the feedback.') }}</span>
        <span class="ny-doc-feedback-label" x-show="sent === 'no'" x-cloak>
            {{ __('Sorry about that.') }}
            <a class="ny-c-blue font-semibold" href="https://github.com/nylo-core/nylo/issues/new?title=Docs%20feedback&body=Page:%20{{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener">{{ __('Tell us what was missing') }} ↗</a>
        </span>

        <div class="flex gap-2.5" x-show="sent === null">
            <button type="button" class="ny-doc-vote ny-doc-vote-yes" @click="sent = 'yes'">{{ __('Yes') }}</button>
            <button type="button" class="ny-doc-vote ny-doc-vote-no" @click="sent = 'no'">{{ __('No') }}</button>
        </div>
    </div>

    {{-- Previous / next --}}
    @if($prevPage || $nextPage)
    {{-- Don't use "Pagination" as a translation key: it collides with Laravel's pagination.php lang file. --}}
    <nav class="ny-doc-pager" aria-label="{{ __('Page navigation') }}">
        @if($prevPage)
        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'page' => $prevPage['page'], 'version' => $version]) }}" class="ny-doc-pager-card">
            <div class="ny-doc-pager-eyebrow">← {{ __('Previous') }}</div>
            <div class="ny-doc-pager-title">{{ $pageTitle($prevPage['page']) }}</div>
        </a>
        @else
        <span></span>
        @endif

        @if($nextPage)
        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'page' => $nextPage['page'], 'version' => $version]) }}" class="ny-doc-pager-card text-right">
            <div class="ny-doc-pager-eyebrow">{{ __('Next') }} →</div>
            <div class="ny-doc-pager-title">{{ $pageTitle($nextPage['page']) }}</div>
        </a>
        @endif
    </nav>
    @endif

    {{-- Colophon --}}
    <div class="ny-doc-colophon">
        <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>

        <div class="flex items-center gap-5">
            @if(Route::currentRouteName() === 'landing.docs')
                <x-language-switcher />
            @endif

            <a href="https://www.youtube.com/@nylo_dev" target="_blank" rel="noopener" class="ny-c-muted hover:ny-c-ink transition-colors">
                <span class="sr-only">YouTube</span>
                <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
            <a href="https://github.com/nylo-core/nylo" target="_blank" rel="noopener" class="ny-c-muted hover:ny-c-ink transition-colors">
                <span class="sr-only">GitHub</span>
                <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
            </a>
            <a href="https://twitter.com/nylo_dev" target="_blank" rel="noopener" class="ny-c-muted hover:ny-c-ink transition-colors">
                <span class="sr-only">Twitter</span>
                <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
        </div>
    </div>
</footer>
