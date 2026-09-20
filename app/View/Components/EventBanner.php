<?php

namespace App\View\Components;

use App\Http\Services\EventService;
use App\Support\OnlineEvent;
use Illuminate\View\Component;

/**
 * The site-wide countdown bar for the next online event. Renders nothing
 * between events, so layouts can include it unconditionally.
 */
class EventBanner extends Component
{
    public ?OnlineEvent $event;

    public function __construct(EventService $events)
    {
        $this->event = $events->current();
    }

    /**
     * Skip the bar on the event's own page, which already carries a larger
     * countdown and the Register card.
     */
    public function shouldRender(): bool
    {
        return $this->event !== null && ! request()->routeIs('landing.event');
    }

    public function render()
    {
        return view('components.event-banner');
    }
}
