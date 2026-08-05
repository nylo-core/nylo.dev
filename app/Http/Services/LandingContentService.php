<?php

namespace App\Http\Services;

use App\Models\OnlineEvent;

/**
 * Class LandingContentService
 *
 * Copy, links and promo data for the marketing landing page. Content lives in
 * a class rather than config so __() resolves against the request locale.
 */
class LandingContentService
{
    /**
     * The online event currently promoted on the landing page, if any.
     */
    public function currentEvent(): ?OnlineEvent
    {
        $event = cache()->remember('event', now()->addHours(1), function () {
            return OnlineEvent::where('end_date', '>', now())->first() ?? false;
        });

        return $event ?: null;
    }

    /**
     * Standalone documentation links the landing page needs by name.
     *
     * @return array<string, string>
     */
    public function docsUrls(string $version): array
    {
        return [
            'index' => $this->docsUrl($version),
            'installation' => $this->docsUrl($version, 'installation'),
            'metro' => $this->docsUrl($version, 'metro'),
            'networking' => $this->docsUrl($version, 'networking'),
        ];
    }

    /**
     * The six headline features. Metro is rendered as the dark card and so is
     * not listed here.
     *
     * @return array<int, array<string, string>>
     */
    public function pillars(string $version): array
    {
        $pillars = [
            ['num' => '01', 'tag' => 'nyRoutes', 'title' => __('Routing'), 'page' => 'router', 'copy' => __('Declarative routes with guards, typed parameters, transitions and deep linking.')],
            ['num' => '02', 'tag' => 'NyPage', 'title' => __('State management'), 'page' => 'state-management', 'copy' => __('Reactive state with controllers, lifecycle hooks and painless persistence.')],
            ['num' => '03', 'tag' => 'NyApiService', 'title' => __('Networking'), 'page' => 'networking', 'copy' => __('API service classes with automatic model decoding and request interceptors.')],
            ['num' => '04', 'tag' => 'NyFormData', 'title' => __('Forms'), 'page' => 'forms', 'copy' => __('Declare fields once — validation, casting and data binding come with them.')],
            ['num' => '05', 'tag' => 'AuthGuard', 'title' => __('Authentication'), 'page' => 'authentication', 'copy' => __('Session handling, token storage and route guards without a bespoke setup.')],
        ];

        return array_map(function (array $pillar) use ($version) {
            $pillar['url'] = $this->docsUrl($version, $pillar['page']);

            return $pillar;
        }, $pillars);
    }

    /**
     * The "Also included" chip row beneath the feature grid.
     *
     * @return array<int, array<string, string>>
     */
    public function extraFeatures(string $version): array
    {
        $features = [
            ['label' => __('Events'), 'page' => 'events'],
            ['label' => __('Scheduler'), 'page' => 'scheduler'],
            ['label' => __('Storage'), 'page' => 'storage'],
            ['label' => __('Localization'), 'page' => 'localization'],
            ['label' => __('Nav Hub'), 'page' => 'navigation-hub'],
            ['label' => __('Themes & styling'), 'page' => 'themes-and-styling'],
            ['label' => __('Providers'), 'page' => 'providers'],
            ['label' => __('Interceptors'), 'page' => 'networking'],
            ['label' => __('App icons'), 'page' => 'app-icons'],
        ];

        return array_map(function (array $feature) use ($version) {
            $feature['url'] = $this->docsUrl($version, $feature['page']);

            return $feature;
        }, $features);
    }

    /**
     * Bullet points in the Metro CLI section.
     *
     * @return array<int, string>
     */
    public function metroHighlights(): array
    {
        return [
            __('17 generators covering widgets, app plumbing and config'),
            __('Routes registered automatically on generate'),
            __('Consistent naming and file placement across the team'),
        ];
    }

    /**
     * Tabs in the code explorer, keyed by the overview component they show.
     *
     * @return array<string, string>
     */
    public function explorerTabs(): array
    {
        return [
            'routing' => __('Routing'),
            'authentication' => __('Auth'),
            'forms' => __('Forms'),
            'state-management' => __('State'),
            'events' => __('Events'),
            'scheduler' => __('Scheduler'),
            'networking' => __('Networking'),
            'storage' => __('Storage'),
            'localization' => __('Localization'),
            'navigation-hub' => __('Nav Hub'),
        ];
    }

    /**
     * Community quotes, split by the slot each one fills in the layout.
     *
     * @return array{featured: array<string, string>, highlights: array<int, array<string, string>>, rest: array<int, array<string, string>>}
     */
    public function testimonials(): array
    {
        return [
            'featured' => [
                'quote' => __("I'm new to Dart and new to your framework — which I love."),
                'author' => 'Peter',
                'role' => __('Senior Director, Heroku Global'),
            ],
            'highlights' => [
                ['quote' => __('Nylo is the best framework for Flutter — it makes developing easy.'), 'author' => '@higakijin'],
                ['quote' => __('By far the best framework out there. Amazing quality and features.'), 'author' => '@2kulfi'],
            ],
            'rest' => [
                ['quote' => __('It makes the work easier and less time consuming. Great work.'), 'author' => 'darkreader01'],
                ['quote' => __("Just discovered this framework and I'm very impressed. Thank you."), 'author' => '@lepresk'],
                ['quote' => __('Really love the concept of this framework.'), 'author' => '@Chrisvidal'],
                ['quote' => __('I wanted to thank you guys for the great job you are doing.'), 'author' => '@youssefKadaouiAbbassi'],
                ['quote' => __("Just to say that I am in love with @nylo_dev's website!"), 'author' => '@esfoliante_txt'],
                ['quote' => __('This is incredible. Very well done!'), 'author' => 'FireflyDaniel'],
            ],
        ];
    }

    /**
     * Build a documentation URL for the current locale. Passing no page links
     * to the docs index.
     */
    private function docsUrl(string $version, ?string $page = null): string
    {
        return route('landing.docs', array_filter([
            'locale' => app()->getLocale(),
            'version' => $version,
            'page' => $page,
        ]));
    }
}
