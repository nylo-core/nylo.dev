<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EventPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The header asks GitHub for the star count.
        Http::fake();

        $this->configure([]);
    }

    /**
     * Test an event page loads with its copy.
     */
    public function test_event_page_loads(): void
    {
        $this->configure([$this->event([
            'tagline' => 'Ship a Flutter app before the kettle boils.',
            'description' => 'The long version of the pitch.',
            'highlights' => ['Scaffolding with Metro', 'Routing and state'],
        ])]);

        $response = $this->get('/events/nylo-live');

        $response->assertStatus(200);
        $response->assertViewIs('pages.event');
        $response->assertSee('Nylo Live');
        $response->assertSee('Ship a Flutter app before the kettle boils.');
        $response->assertSee('The long version of the pitch.');
        $response->assertSee('Scaffolding with Metro');
        $response->assertSee('Routing and state');
    }

    /**
     * Test the Register button carries Luma's embed hooks so the card opens
     * over the page rather than sending people to lu.ma.
     */
    public function test_event_page_opens_the_luma_card_in_place(): void
    {
        $this->configure([$this->event(['luma_event_id' => 'evt-123456789'])]);

        $response = $this->get('/events/nylo-live');

        $response->assertStatus(200);
        $response->assertSee('luma-checkout--button', false);
        $response->assertSee('data-luma-event-id="evt-123456789"', false);
        $response->assertSee('https://embed.lu.ma/checkout-button.js', false);
        // The href stays useful if the script never loads.
        $response->assertSee('https://lu.ma/nylo-live', false);
    }

    /**
     * Test an event without a Luma event ID just links out.
     */
    public function test_event_page_links_out_without_a_luma_event_id(): void
    {
        $this->configure([$this->event()]);

        $response = $this->get('/events/nylo-live');

        $response->assertStatus(200);
        $response->assertSee('https://lu.ma/nylo-live', false);
        $response->assertDontSee('luma-checkout--button', false);
        $response->assertDontSee('embed.lu.ma/checkout-button.js', false);
    }

    /**
     * Test the page publishes Event structured data for search engines.
     */
    public function test_event_page_publishes_event_structured_data(): void
    {
        $this->configure([$this->event()]);

        $response = $this->get('/events/nylo-live');

        $response->assertSee('"@type":"Event"', false);
        $response->assertSee('OnlineEventAttendanceMode', false);
        $response->assertSee('"startDate":', false);
        $response->assertSee('"@type":"VirtualLocation"', false);
    }

    /**
     * Test an unknown slug 404s.
     */
    public function test_unknown_event_returns_404(): void
    {
        $this->configure([$this->event()]);

        $this->get('/events/not-a-real-event')->assertStatus(404);
    }

    /**
     * Test a finished event leaves the site.
     */
    public function test_finished_event_returns_404(): void
    {
        $this->configure([$this->event([
            'starts_at' => '-1 day',
            'ends_at' => '-23 hours',
        ])]);

        $this->get('/events/nylo-live')->assertStatus(404);
    }

    /**
     * Test the countdown bar runs on the home page while an event is on.
     */
    public function test_banner_shows_on_the_home_page(): void
    {
        $this->configure([$this->event(['tagline' => 'The one line that sells it.'])]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ny-event-bar', false);
        $response->assertSee('The one line that sells it.');
        $response->assertSee(route('landing.event', ['slug' => 'nylo-live']), false);
        // The standing v7 bar steps aside while an event is running.
        $response->assertDontSee('ny-announce-tag', false);
    }

    /**
     * Test the bar reaches the docs, where most of the traffic is.
     */
    public function test_banner_shows_in_the_docs(): void
    {
        $this->configure([$this->event(['tagline' => 'The one line that sells it.'])]);

        $response = $this->get('/docs/7.x/installation');

        $response->assertStatus(200);
        $response->assertSee('ny-event-bar', false);
        $response->assertSee('The one line that sells it.');
    }

    /**
     * Test the bar is left off the event's own page, which already carries a
     * countdown and the Register card.
     */
    public function test_banner_is_not_repeated_on_the_event_page(): void
    {
        $this->configure([$this->event()]);

        $response = $this->get('/events/nylo-live');

        $response->assertStatus(200);
        $response->assertDontSee('ny-event-bar', false);
    }

    /**
     * Test the bar holds off until the announce date, while the page itself
     * is already live and shareable.
     */
    public function test_banner_waits_for_the_announce_date(): void
    {
        $this->configure([$this->event(['announce_at' => '+2 days'])]);

        $home = $this->get('/');
        $home->assertDontSee('ny-event-bar', false);
        $home->assertSee('ny-announce-tag', false);

        $this->get('/events/nylo-live')->assertStatus(200);
    }

    /**
     * Test the bar speaks the language of the page it lands on — it rides
     * along on all 16 locales.
     */
    public function test_banner_is_translated_on_a_localised_page(): void
    {
        $this->configure([$this->event()]);

        $response = $this->get('/fr/');

        $response->assertStatus(200);
        $response->assertSee('ny-event-bar', false);
        $response->assertSee("S'inscrire");
        $response->assertSee('En direct le');
        $response->assertDontSee('>Register<', false);
    }

    /**
     * Test the v7 announcement keeps the top bar between events.
     */
    public function test_v7_announcement_shows_when_no_event_is_running(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ny-announce-tag', false);
        $response->assertDontSee('ny-event-bar', false);
    }

    /**
     * Put a set of events into config, as config/project/events.php would.
     *
     * @param  array<int, mixed>  $events
     */
    private function configure(array $events): void
    {
        config()->set('project.events', [
            'timezone' => config('app.timezone'),
            'events' => $events,
        ]);
    }

    /**
     * A valid event entry. Relative dates resolve against the test clock.
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
