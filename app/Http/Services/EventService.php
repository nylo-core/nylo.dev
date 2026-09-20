<?php

namespace App\Http\Services;

use App\Support\OnlineEvent;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Class EventService
 *
 * Reads the online events from config/project/events.php. Events that have
 * finished are dropped here, so nothing downstream has to check the clock.
 */
class EventService
{
    /**
     * The event promoted across the site right now — the soonest one that has
     * not finished and has passed its announce date. Null between events.
     */
    public function current(): ?OnlineEvent
    {
        foreach ($this->live() as $event) {
            if ($event->isAnnounced()) {
                return $event;
            }
        }

        return null;
    }

    /**
     * Find an event by slug. Finished events leave the site, so this returns
     * null for them and the page 404s. Announced or not, the page is live, so
     * a link can be shared before the banner goes up.
     */
    public function find(string $slug): ?OnlineEvent
    {
        foreach ($this->live() as $event) {
            if ($event->slug === $slug) {
                return $event;
            }
        }

        return null;
    }

    /**
     * Every configured event that has not finished yet, soonest first.
     *
     * @return list<OnlineEvent>
     */
    public function live(): array
    {
        $events = array_filter(
            $this->hydrate(),
            fn (OnlineEvent $event) => ! $event->hasEnded()
        );

        // usort reindexes, so the filtered array comes back out as a list.
        usort($events, fn (OnlineEvent $a, OnlineEvent $b) => $a->startsAt <=> $b->startsAt);

        return $events;
    }

    /**
     * Build the value objects. A malformed entry is skipped rather than taken
     * out on the whole page — a typo in config should cost the banner, not
     * the site — so the reason is logged for whoever added it.
     *
     * @return list<OnlineEvent>
     */
    private function hydrate(): array
    {
        $timezone = config('project.events.timezone') ?: config('app.timezone');

        $events = [];

        foreach (config('project.events.events', []) as $index => $event) {
            if (! is_array($event)) {
                continue;
            }

            try {
                $events[] = OnlineEvent::fromConfig($event, $timezone);
            } catch (InvalidArgumentException $e) {
                Log::warning("Skipping online event [{$index}] in config/project/events.php: {$e->getMessage()}");
            }
        }

        return $events;
    }
}
