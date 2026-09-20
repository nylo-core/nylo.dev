{{-- Site-wide countdown bar for the next online event. Rendered by
     App\View\Components\EventBanner, which hides it between events. --}}
<div
    x-data="eventBanner('{{ $event->slug }}', '{{ $event->startsAtIso() }}', '{{ $event->endsAtIso() }}')"
    x-show="open"
    x-cloak
    class="ny-event-bar"
    role="region"
    aria-label="{{ __('Upcoming event') }}"
>
    <div class="ny-shell relative py-2.5">
        <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1.5 pr-7">

            {{-- Countdown until the doors open, then a live pulse --}}
            <span x-show="!live" class="ny-event-clock tabular-nums" x-text="countdown">{{ $event->shortDate() }}</span>
            <span x-show="live" class="ny-event-clock ny-event-clock-live">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                </span>
                {{ __('Live now') }}
            </span>

            {{-- The full sentence needs a tablet; a phone gets the date, and the
                 button next to it, which is all that has to fit. --}}
            <span class="ny-event-copy hidden min-w-0 sm:block">
                <span x-show="!live" class="font-semibold">{{ __('Live :date', ['date' => $event->shortDate()]) }}:</span>
                {{ $event->bannerText() }}
            </span>
            <span x-show="!live" class="ny-event-copy font-semibold sm:hidden">{{ __('Live :date', ['date' => $event->shortDate()]) }}</span>

            <a href="{{ $event->url() }}" class="ny-event-cta">
                <span x-show="!live">{{ __('Register') }}</span>
                <span x-show="live">{{ __('Join now') }}</span>
            </a>
        </div>

        <button
            type="button"
            @click="dismiss()"
            class="ny-event-close"
            aria-label="{{ __('Dismiss') }}"
        >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" d="M5 5l10 10M15 5L5 15" />
            </svg>
        </button>
    </div>
</div>
