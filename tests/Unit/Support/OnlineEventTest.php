<?php

namespace Tests\Unit\Support;

use App\Support\OnlineEvent;
use Carbon\CarbonImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OnlineEventTest extends TestCase
{
    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    /**
     * Test every configured field reaches the value object.
     */
    public function test_from_config_hydrates_every_field(): void
    {
        $event = OnlineEvent::fromConfig([
            'slug' => 'nylo-live',
            'title' => 'Nylo Live',
            'tagline' => 'One line for the banner.',
            'description' => 'A longer blurb.',
            'starts_at' => '2099-06-01 16:30',
            'ends_at' => '2099-06-01 17:15',
            'announce_at' => '2099-05-01 09:00',
            'host' => 'Anthony',
            'location' => 'Virtual',
            'image' => 'images/events/nylo-live.png',
            'luma_url' => 'https://lu.ma/nylo-live',
            'luma_event_id' => 'evt-123456789',
            'highlights' => ['Metro', 'Routing'],
        ], 'Europe/London');

        $this->assertSame('nylo-live', $event->slug);
        $this->assertSame('Nylo Live', $event->title);
        $this->assertSame('One line for the banner.', $event->tagline);
        $this->assertSame('A longer blurb.', $event->description);
        $this->assertSame('Anthony', $event->host());
        $this->assertSame('Virtual', $event->location);
        $this->assertSame('evt-123456789', $event->lumaEventId);
        $this->assertSame(['Metro', 'Routing'], $event->highlights);
        $this->assertSame('2099-06-01 16:30', $event->startsAt->format('Y-m-d H:i'));
        $this->assertSame('2099-05-01 09:00', $event->announceAt?->format('Y-m-d H:i'));
        $this->assertStringContainsString('images/events/nylo-live.png', (string) $event->imageUrl());
    }

    /**
     * Test the optional fields fall back sensibly.
     */
    public function test_from_config_falls_back_to_sensible_defaults(): void
    {
        $event = $this->event();

        $this->assertNull($event->tagline);
        $this->assertNull($event->description);
        $this->assertNull($event->image);
        $this->assertNull($event->imageUrl());
        $this->assertNull($event->announceAt);
        $this->assertSame([], $event->highlights);
        $this->assertSame('Virtual', $event->location);
        $this->assertSame(config('app.name'), $event->host());
        $this->assertTrue($event->isAnnounced());

        // The banner falls back to the title when there is no tagline.
        $this->assertSame($event->title, $event->bannerText());
    }

    /**
     * Test an entry missing a required key is rejected.
     */
    #[DataProvider('requiredKeys')]
    public function test_from_config_rejects_an_entry_without_required_keys(string $missing): void
    {
        $config = [
            'slug' => 'nylo-live',
            'title' => 'Nylo Live',
            'starts_at' => '2099-06-01 16:30',
            'ends_at' => '2099-06-01 17:15',
            'luma_url' => 'https://lu.ma/nylo-live',
        ];

        unset($config[$missing]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("[{$missing}]");

        OnlineEvent::fromConfig($config);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function requiredKeys(): array
    {
        return [['slug'], ['title'], ['starts_at'], ['ends_at'], ['luma_url']];
    }

    /**
     * Test the state helpers track the clock.
     */
    public function test_state_helpers_track_the_clock(): void
    {
        $event = $this->event([
            'starts_at' => '2099-06-01 16:30',
            'ends_at' => '2099-06-01 17:15',
        ]);

        $this->freezeAt('2099-06-01 10:00');
        $this->assertTrue($event->isUpcoming());
        $this->assertFalse($event->isLive());
        $this->assertFalse($event->hasEnded());

        $this->freezeAt('2099-06-01 16:45');
        $this->assertFalse($event->isUpcoming());
        $this->assertTrue($event->isLive());
        $this->assertFalse($event->hasEnded());

        $this->freezeAt('2099-06-01 18:00');
        $this->assertFalse($event->isUpcoming());
        $this->assertFalse($event->isLive());
        $this->assertTrue($event->hasEnded());
    }

    /**
     * Test an announce date in the future keeps the event unannounced.
     */
    public function test_announce_date_gates_promotion(): void
    {
        $event = $this->event([
            'starts_at' => '2099-06-01 16:30',
            'ends_at' => '2099-06-01 17:15',
            'announce_at' => '2099-05-01 09:00',
        ]);

        $this->freezeAt('2099-04-30 09:00');
        $this->assertFalse($event->isAnnounced());

        $this->freezeAt('2099-05-01 09:00');
        $this->assertTrue($event->isAnnounced());
    }

    /**
     * Test registration only opens in place when Luma's event ID is known.
     */
    public function test_opens_in_place_only_with_a_luma_event_id(): void
    {
        $this->assertFalse($this->event()->opensInPlace());
        $this->assertTrue($this->event(['luma_event_id' => 'evt-123456789'])->opensInPlace());
    }

    /**
     * Test times are rendered in the event's own timezone.
     */
    public function test_times_are_rendered_in_the_events_timezone(): void
    {
        $event = $this->event([
            'starts_at' => '2099-06-01 16:30',
            'ends_at' => '2099-06-01 17:15',
        ], 'Europe/London');

        $this->assertSame('16:30 - 17:15 BST', $event->timeRange());
        $this->assertSame('Jun 1', $event->shortDate());
        $this->assertSame('Jun 1, 16:30 BST', $event->shortDateTime());
        $this->assertSame('JUN', $event->monthLabel());
        $this->assertSame('1', $event->dayLabel());
        $this->assertStringContainsString('June', $event->longDate());
    }

    /**
     * Test the ISO timestamps carry an offset, which is what the countdown in
     * the browser needs to land on the right instant.
     */
    public function test_iso_timestamps_carry_the_offset(): void
    {
        $event = $this->event([
            'starts_at' => '2099-06-01 16:30',
            'ends_at' => '2099-06-01 17:15',
        ], 'Europe/London');

        $this->assertSame('2099-06-01T16:30:00+01:00', $event->startsAtIso());
        $this->assertSame('2099-06-01T17:15:00+01:00', $event->endsAtIso());
    }

    /**
     * Test the duration reads in whole minutes or hours.
     */
    public function test_duration_label_reads_naturally(): void
    {
        $this->assertSame('45 minutes', $this->event([
            'starts_at' => '2099-06-01 16:30', 'ends_at' => '2099-06-01 17:15',
        ])->durationLabel());

        $this->assertSame('1 hour', $this->event([
            'starts_at' => '2099-06-01 16:00', 'ends_at' => '2099-06-01 17:00',
        ])->durationLabel());

        $this->assertSame('2 hours', $this->event([
            'starts_at' => '2099-06-01 16:00', 'ends_at' => '2099-06-01 18:00',
        ])->durationLabel());
    }

    /**
     * Test the event links to its own page on this site.
     */
    public function test_url_points_at_the_event_page(): void
    {
        $this->assertSame(
            route('landing.event', ['slug' => 'nylo-live']),
            $this->event()->url()
        );
    }

    /**
     * Move the clock to an instant written in the same zone the test events
     * use, rather than the app's own (Asia/Bangkok).
     */
    private function freezeAt(string $instant, string $timezone = 'UTC'): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse($instant, $timezone));
    }

    /**
     * Build an event with sane defaults.
     *
     * @param  array<string, mixed>  $overrides
     */
    private function event(array $overrides = [], string $timezone = 'UTC'): OnlineEvent
    {
        return OnlineEvent::fromConfig(array_merge([
            'slug' => 'nylo-live',
            'title' => 'Nylo Live',
            'starts_at' => '2099-06-01 16:30',
            'ends_at' => '2099-06-01 17:15',
            'luma_url' => 'https://lu.ma/nylo-live',
        ], $overrides), $timezone);
    }
}
