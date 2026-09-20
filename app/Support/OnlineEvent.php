<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * A single online event, hydrated from config/project/events.php.
 *
 * Events are config rather than database rows: there are about four a year,
 * and each one ships with a cover image, so it is a deploy either way.
 */
final class OnlineEvent
{
    /**
     * Keys an event entry cannot be built without.
     *
     * @var list<string>
     */
    private const REQUIRED = ['slug', 'title', 'starts_at', 'ends_at', 'luma_url'];

    /**
     * @param  list<string>  $highlights
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly CarbonImmutable $startsAt,
        public readonly CarbonImmutable $endsAt,
        public readonly string $lumaUrl,
        public readonly ?string $lumaEventId = null,
        public readonly ?string $tagline = null,
        public readonly ?string $description = null,
        public readonly ?string $image = null,
        public readonly ?string $host = null,
        public readonly string $location = 'Virtual',
        public readonly ?CarbonImmutable $announceAt = null,
        public readonly array $highlights = [],
    ) {}

    /**
     * Build an event from one config entry.
     *
     * @param  array<string, mixed>  $event
     *
     * @throws \InvalidArgumentException when a required key is missing
     */
    public static function fromConfig(array $event, string $fallbackTimezone = 'UTC'): self
    {
        foreach (self::REQUIRED as $key) {
            if (blank($event[$key] ?? null)) {
                throw new InvalidArgumentException("Event is missing the [{$key}] key.");
            }
        }

        $timezone = $event['timezone'] ?? $fallbackTimezone;

        return new self(
            slug: $event['slug'],
            title: $event['title'],
            startsAt: CarbonImmutable::parse($event['starts_at'], $timezone),
            endsAt: CarbonImmutable::parse($event['ends_at'], $timezone),
            lumaUrl: $event['luma_url'],
            lumaEventId: $event['luma_event_id'] ?? null,
            tagline: $event['tagline'] ?? null,
            description: $event['description'] ?? null,
            image: $event['image'] ?? null,
            host: $event['host'] ?? null,
            location: $event['location'] ?? 'Virtual',
            announceAt: filled($event['announce_at'] ?? null)
                ? CarbonImmutable::parse($event['announce_at'], $timezone)
                : null,
            highlights: array_values($event['highlights'] ?? []),
        );
    }

    /**
     * Whether the event is streaming right now.
     */
    public function isLive(): bool
    {
        return CarbonImmutable::now()->betweenIncluded($this->startsAt, $this->endsAt);
    }

    /**
     * Whether the event has not started yet.
     */
    public function isUpcoming(): bool
    {
        return CarbonImmutable::now()->lt($this->startsAt);
    }

    /**
     * Whether the event is over. Finished events leave the site entirely.
     */
    public function hasEnded(): bool
    {
        return CarbonImmutable::now()->gt($this->endsAt);
    }

    /**
     * Whether the event may be promoted yet. An entry can sit in config with
     * its page live and shareable while the banner waits for `announce_at`.
     */
    public function isAnnounced(): bool
    {
        return $this->announceAt === null || CarbonImmutable::now()->gte($this->announceAt);
    }

    /**
     * Whether Register can open Luma's card in place rather than linking out.
     */
    public function opensInPlace(): bool
    {
        return filled($this->lumaEventId);
    }

    /**
     * The event page on this site.
     */
    public function url(): string
    {
        return route('landing.event', ['slug' => $this->slug]);
    }

    /**
     * Absolute URL of the cover image, if the event has one.
     */
    public function imageUrl(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }

    /**
     * Who is running the event.
     */
    public function host(): string
    {
        return $this->host ?? config('app.name');
    }

    /**
     * The single line the banner shows.
     */
    public function bannerText(): string
    {
        return $this->tagline ?? $this->title;
    }

    /**
     * Start time as an ISO 8601 string with offset, for the countdown in JS.
     */
    public function startsAtIso(): string
    {
        return $this->startsAt->toIso8601String();
    }

    /**
     * End time as an ISO 8601 string with offset, for the countdown in JS.
     */
    public function endsAtIso(): string
    {
        return $this->endsAt->toIso8601String();
    }

    /**
     * Short date for the banner, e.g. "Sept 24".
     */
    public function shortDate(): string
    {
        return $this->startsAt->translatedFormat('M j');
    }

    /**
     * Compact date and start time, e.g. "Sep 24, 16:30 BST".
     */
    public function shortDateTime(): string
    {
        return sprintf(
            '%s, %s %s',
            $this->shortDate(),
            $this->startsAt->translatedFormat('H:i'),
            $this->timezoneLabel()
        );
    }

    /**
     * Full date for the event page, e.g. "Thursday 24 September".
     */
    public function longDate(): string
    {
        return $this->startsAt->translatedFormat('l j F');
    }

    /**
     * The event's timezone, e.g. "Europe/London".
     */
    public function timezoneName(): string
    {
        return $this->startsAt->timezoneName;
    }

    /**
     * Abbreviated month for the date card, e.g. "SEPT".
     */
    public function monthLabel(): string
    {
        return mb_strtoupper($this->startsAt->translatedFormat('M'));
    }

    /**
     * Day of the month for the date card.
     */
    public function dayLabel(): string
    {
        return $this->startsAt->translatedFormat('j');
    }

    /**
     * The run time in the event's own timezone, e.g. "16:30 - 17:15 BST".
     */
    public function timeRange(): string
    {
        return sprintf(
            '%s - %s %s',
            $this->startsAt->translatedFormat('H:i'),
            $this->endsAt->translatedFormat('H:i'),
            $this->timezoneLabel()
        );
    }

    /**
     * Timezone abbreviation for the start time, e.g. "BST". Zones without an
     * abbreviation fall back to the offset, which is what format('T') gives.
     */
    public function timezoneLabel(): string
    {
        return $this->startsAt->format('T');
    }

    /**
     * How long the event runs, e.g. "45 minutes". Carbon knows the plural
     * rules for every locale the site ships, so this is not translated here.
     */
    public function durationLabel(): string
    {
        $duration = $this->startsAt->diffAsCarbonInterval($this->endsAt);
        $duration->locale(app()->getLocale());

        return $duration->forHumans();
    }
}
