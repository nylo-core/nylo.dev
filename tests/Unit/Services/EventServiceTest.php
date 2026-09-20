<?php

namespace Tests\Unit\Services;

use App\Http\Services\EventService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EventServiceTest extends TestCase
{
    private EventService $events;

    protected function setUp(): void
    {
        parent::setUp();
        $this->events = new EventService;
        $this->configure([]);
    }

    /**
     * Test current returns null when nothing is configured.
     */
    public function test_current_returns_null_when_no_events_are_configured(): void
    {
        $this->assertNull($this->events->current());
    }

    /**
     * Test current promotes the soonest event still to come.
     */
    public function test_current_returns_the_soonest_upcoming_event(): void
    {
        $this->configure([
            $this->event(['slug' => 'later', 'starts_at' => '+3 weeks', 'ends_at' => '+3 weeks 1 hour']),
            $this->event(['slug' => 'sooner', 'starts_at' => '+1 week', 'ends_at' => '+1 week 1 hour']),
        ]);

        $this->assertSame('sooner', $this->events->current()?->slug);
    }

    /**
     * Test current skips events that have already finished.
     */
    public function test_current_ignores_events_that_have_ended(): void
    {
        $this->configure([
            $this->event(['starts_at' => '-2 days', 'ends_at' => '-2 days 1 hour']),
        ]);

        $this->assertNull($this->events->current());
    }

    /**
     * Test an event that is streaming right now is still the current one.
     */
    public function test_current_returns_an_event_that_is_streaming_now(): void
    {
        $this->configure([
            $this->event(['slug' => 'on-air', 'starts_at' => '-10 minutes', 'ends_at' => '+50 minutes']),
        ]);

        $current = $this->events->current();

        $this->assertSame('on-air', $current?->slug);
        $this->assertTrue($current->isLive());
    }

    /**
     * Test the banner holds off until the announce date passes.
     */
    public function test_current_waits_for_the_announce_date(): void
    {
        $this->configure([
            $this->event(['announce_at' => '+2 days']),
        ]);

        $this->assertNull($this->events->current());
    }

    /**
     * Test an event is promoted once its announce date has passed.
     */
    public function test_current_promotes_an_event_once_announced(): void
    {
        $this->configure([
            $this->event(['announce_at' => '-1 day']),
        ]);

        $this->assertNotNull($this->events->current());
    }

    /**
     * Test find returns the event with the given slug.
     */
    public function test_find_returns_the_event_with_that_slug(): void
    {
        $this->configure([
            $this->event(['slug' => 'first']),
            $this->event(['slug' => 'second', 'starts_at' => '+2 weeks', 'ends_at' => '+2 weeks 1 hour']),
        ]);

        $this->assertSame('second', $this->events->find('second')?->slug);
    }

    /**
     * Test find returns null for a slug that is not configured.
     */
    public function test_find_returns_null_for_an_unknown_slug(): void
    {
        $this->configure([$this->event()]);

        $this->assertNull($this->events->find('not-a-real-event'));
    }

    /**
     * Test a finished event leaves the site, so its page 404s.
     */
    public function test_find_returns_null_once_the_event_has_ended(): void
    {
        $this->configure([
            $this->event(['slug' => 'over', 'starts_at' => '-1 day', 'ends_at' => '-23 hours']),
        ]);

        $this->assertNull($this->events->find('over'));
    }

    /**
     * Test the page is live before the announce date so the link can be
     * shared early, even though the banner is still holding off.
     */
    public function test_find_returns_an_event_that_has_not_been_announced(): void
    {
        $this->configure([
            $this->event(['slug' => 'embargoed', 'announce_at' => '+2 days']),
        ]);

        $this->assertNull($this->events->current());
        $this->assertSame('embargoed', $this->events->find('embargoed')?->slug);
    }

    /**
     * Test a malformed entry is logged and skipped rather than breaking every
     * page that carries the banner.
     */
    public function test_a_malformed_entry_is_skipped_without_taking_out_the_rest(): void
    {
        Log::spy();

        $this->configure([
            ['slug' => 'broken', 'title' => 'No dates on this one'],
            $this->event(['slug' => 'fine']),
        ]);

        $this->assertSame('fine', $this->events->current()?->slug);
        Log::shouldHaveReceived('warning')->once();
    }

    /**
     * Test an unparseable date is skipped the same way a missing key is.
     */
    public function test_an_unparseable_date_is_skipped(): void
    {
        Log::spy();

        $this->configure([
            $this->event(['starts_at' => 'the second Tuesday of never']),
        ]);

        $this->assertNull($this->events->current());
        Log::shouldHaveReceived('warning')->once();
    }

    /**
     * Test an event can override the default timezone.
     */
    public function test_an_event_can_override_the_default_timezone(): void
    {
        $this->configure([
            $this->event([
                'starts_at' => '2099-06-01 12:00',
                'ends_at' => '2099-06-01 13:00',
                'timezone' => 'Asia/Tokyo',
            ]),
        ], 'UTC');

        $this->assertSame('2099-06-01T12:00:00+09:00', $this->events->current()?->startsAtIso());
    }

    /**
     * Put a set of events into config, as config/project/events.php would.
     *
     * @param  array<int, mixed>  $events
     */
    private function configure(array $events, ?string $timezone = null): void
    {
        // Default to the app timezone so the relative dates below, which come
        // from now(), are read back in the zone they were written in.
        config()->set('project.events', [
            'timezone' => $timezone ?? config('app.timezone'),
            'events' => $events,
        ]);
    }

    /**
     * A valid event entry. Relative dates are resolved against the test clock.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function event(array $overrides = []): array
    {
        $event = array_merge([
            'slug' => 'nylo-live',
            'title' => 'Nylo Live',
            'starts_at' => '+1 week',
            'ends_at' => '+1 week 1 hour',
            'luma_url' => 'https://lu.ma/nylo-live',
        ], $overrides);

        foreach (['starts_at', 'ends_at', 'announce_at'] as $key) {
            $value = $event[$key] ?? null;

            if (is_string($value) && (str_starts_with($value, '+') || str_starts_with($value, '-'))) {
                $event[$key] = now()->modify($value)->format('Y-m-d H:i:s');
            }
        }

        return $event;
    }
}
