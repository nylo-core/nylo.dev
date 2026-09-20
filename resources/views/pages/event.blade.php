@extends('layouts.app-landing')

@section('content')

<section class="relative overflow-hidden ny-dots">
    <div class="ny-glow-a"></div>
    <div class="ny-glow-b"></div>
    <div class="ny-fade-bottom"></div>

    <div class="relative ny-shell pt-12 pb-20 lg:pt-16 lg:pb-24">

        {{-- ===== HEADER ===== --}}
        <div class="ny-rise max-w-[760px]">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                <span class="ny-event-eyebrow">
                    @if ($event->isLive())
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-current opacity-75"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-current"></span>
                        </span>
                        {{ __('Live now') }}
                    @else
                        {{ __('Online event') }}
                    @endif
                </span>
                <span class="ny-sep"></span>
                <span class="ny-eyebrow-muted">{{ $event->location }}</span>
            </div>

            <h1 class="ny-h1 mt-6">{{ $event->title }}</h1>

            @if ($event->tagline)
                <p class="ny-lede ny-lede-lg mt-5">{{ $event->tagline }}</p>
            @endif

            <p class="ny-text ny-text-sm ny-c-muted mt-5">
                {{ __('Hosted by :host', ['host' => $event->host()]) }}
            </p>
        </div>

        {{-- ===== BODY ===== --}}
        <div class="mt-12 grid gap-10 lg:mt-14 lg:grid-cols-[1.05fr_.95fr] lg:gap-14">

            <div class="min-w-0">
                @if ($event->imageUrl())
                    <div class="ny-event-cover">
                        <img src="{{ $event->imageUrl() }}" alt="{{ $event->title }}" class="w-full" width="1200" height="630">
                    </div>
                @endif

                @if ($event->description)
                    <div class="@if($event->imageUrl()) mt-10 @endif">
                        <h2 class="ny-h4">{{ __('About this event') }}</h2>
                        <div class="mt-4 space-y-4">
                            @foreach (preg_split('/\R{2,}/', trim($event->description)) as $paragraph)
                                <p class="ny-text ny-text-md">{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (count($event->highlights))
                    <div class="mt-10">
                        <h2 class="ny-h4">{{ __("What we'll cover") }}</h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($event->highlights as $highlight)
                                <li class="ny-event-highlight">
                                    <span class="ny-event-highlight-tick" aria-hidden="true">&check;</span>
                                    <span>{{ $highlight }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- ===== REGISTRATION ===== --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="ny-event-panel">
                    <div class="ny-event-panel-head px-5 py-3.5">{{ __('Registration') }}</div>

                    <div class="p-5">
                        {{-- When --}}
                        <div class="flex items-center gap-3.5">
                            <div class="ny-event-datecard">
                                <span class="ny-event-datecard-month">{{ $event->monthLabel() }}</span>
                                <span class="ny-event-datecard-day">{{ $event->dayLabel() }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="ny-text ny-text-md ny-c-ink font-semibold">{{ $event->longDate() }}</p>
                                <p class="ny-text ny-text-sm ny-c-muted mt-1">{{ $event->timeRange() }}</p>
                                {{-- Restated in the visitor's own timezone, unless it matches --}}
                                <p
                                    class="ny-text ny-text-sm ny-c-muted mt-1"
                                    x-data="eventLocalTime('{{ $event->startsAtIso() }}', '{{ $event->endsAtIso() }}', '{{ $event->timezoneName() }}')"
                                    x-show="label"
                                    x-cloak
                                >{{ __('Your time') }}: <span x-text="label"></span></p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="ny-chip gap-2 !py-1.5 !text-[12.5px]">
                                <span class="iconify lucide--video shrink-0 size-3.5" aria-hidden="true"></span>
                                {{ $event->location }}
                            </span>
                            <span class="ny-chip gap-2 !py-1.5 !text-[12.5px]">
                                <span class="iconify lucide--clock shrink-0 size-3.5" aria-hidden="true"></span>
                                {{ $event->durationLabel() }}
                            </span>
                        </div>

                        {{-- Countdown --}}
                        <div
                            class="mt-5"
                            x-data="eventCountdown('{{ $event->startsAtIso() }}', '{{ $event->endsAtIso() }}')"
                            x-cloak
                        >
                            <div class="ny-event-count" x-show="!live && !ended">
                                <div class="ny-event-count-box">
                                    <span class="ny-event-count-num" x-text="days">0</span>
                                    <span class="ny-event-count-label">{{ __('Days') }}</span>
                                </div>
                                <div class="ny-event-count-box">
                                    <span class="ny-event-count-num" x-text="hours">00</span>
                                    <span class="ny-event-count-label">{{ __('Hrs') }}</span>
                                </div>
                                <div class="ny-event-count-box">
                                    <span class="ny-event-count-num" x-text="minutes">00</span>
                                    <span class="ny-event-count-label">{{ __('Min') }}</span>
                                </div>
                                <div class="ny-event-count-box">
                                    <span class="ny-event-count-num" x-text="seconds">00</span>
                                    <span class="ny-event-count-label">{{ __('Sec') }}</span>
                                </div>
                            </div>

                            <p class="ny-text ny-text-sm ny-c-blue font-semibold" x-show="live">
                                {{ __('The event is streaming right now.') }}
                            </p>
                        </div>

                        {{-- Register --}}
                        <div class="mt-5">
                            @if ($event->isLive() || ! $event->opensInPlace())
                                <a
                                    href="{{ $event->lumaUrl }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="ny-event-register"
                                >
                                    {{ $event->isLive() ? __('Join now') : __('Register') }}
                                </a>
                            @else
                                {{-- Luma's embed script opens its registration card over this
                                     page. Without the script the href still works as a link. --}}
                                <a
                                    href="{{ $event->lumaUrl }}"
                                    class="ny-event-register luma-checkout--button"
                                    data-luma-action="checkout"
                                    data-luma-event-id="{{ $event->lumaEventId }}"
                                >
                                    {{ __('Register') }}
                                </a>
                            @endif

                            <p class="ny-text ny-text-sm ny-c-muted mt-3 text-center">
                                {{ __('Free to attend. Registration is handled by Luma.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <p class="ny-text ny-text-sm ny-c-muted mt-4 text-center">
                    <a href="{{ route('landing.home') }}" class="ny-text-link">{{ __('Back to :name', ['name' => config('app.name')]) }}</a>
                </p>
            </aside>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    @if ($event->opensInPlace() && ! $event->isLive())
        <script id="luma-checkout" src="https://embed.lu.ma/checkout-button.js"></script>
    @endif
@endsection
