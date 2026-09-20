<?php

namespace App\Http\Services;

use App\Support\OnlineEvent;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Request;
use SEO;

/**
 * Class SeoService
 */
class SeoService
{
    /**
     * SeoService constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setDefaultSeo();
    }

    /**
     * Sets the default SEO settings for all pages.
     */
    public function setDefaultSeo(): void
    {
        SEO::setDescription(config('app.name').' is an open-source micro-framework for Flutter that makes building apps a breeze. It provides all the basic building blocks to create a modern application.');
        SEO::setCanonical($this->resolveCanonicalUrl());
        SEO::opengraph()->setUrl($this->resolveCanonicalUrl());
        SEO::twitter()->setSite('@nylo_dev');
        SEO::opengraph()->addProperty('type', 'website');
        SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));
    }

    /**
     * Resolves the canonical URL for the current page.
     * Doc pages always canonicalize to the non-localized /docs/{version}/{page} route.
     */
    private function resolveCanonicalUrl(): string
    {
        $routeName = Route::currentRouteName();

        if (in_array($routeName, ['landing.docs', 'landing.docs.default'])) {
            $params = Route::current()->parameters();

            return route('landing.docs.default', [
                'version' => $params['version'] ?? '',
                'page' => $params['page'] ?? 'installation',
            ]);
        }

        return Request::url();
    }

    /**
     * Sets the SEO title.
     *
     * @param  string  $title
     * @return void
     */
    public function setTitle($title)
    {
        SEO::setTitle($title.' | '.config('app.name'));
    }

    /**
     * Sets the SEO for an online event page, including the Event structured
     * data that puts it in Google's event listings.
     */
    public function setSeoViewingEvent(OnlineEvent $event): void
    {
        $description = $event->tagline ?: Str::limit(strip_tags((string) $event->description), 155);

        $this->setTitle($event->title);
        SEO::setDescription($description);
        SEO::setCanonical($event->url());

        SEO::opengraph()->setTitle($event->title);
        SEO::opengraph()->setDescription($description);
        SEO::opengraph()->setUrl($event->url());

        SEO::twitter()->setTitle($event->title);
        SEO::twitter()->setDescription($description);

        if ($image = $event->imageUrl()) {
            SEO::opengraph()->addImage($image);
            SEO::twitter()->setImage($image);
        }

        // SEO::generate() renders jsonLdMulti(), so the structured data has to
        // be written there — anything set on jsonLd() never reaches the page.
        SEO::jsonLdMulti()->setType('Event');
        SEO::jsonLdMulti()->setTitle($event->title);
        SEO::jsonLdMulti()->setDescription($description);
        SEO::jsonLdMulti()->setUrl($event->url());
        SEO::jsonLdMulti()->addValue('startDate', $event->startsAtIso());
        SEO::jsonLdMulti()->addValue('endDate', $event->endsAtIso());
        SEO::jsonLdMulti()->addValue('eventStatus', 'https://schema.org/EventScheduled');
        SEO::jsonLdMulti()->addValue('eventAttendanceMode', 'https://schema.org/OnlineEventAttendanceMode');
        SEO::jsonLdMulti()->addValue('location', [
            '@type' => 'VirtualLocation',
            'url' => $event->lumaUrl,
        ]);
        SEO::jsonLdMulti()->addValue('organizer', [
            '@type' => 'Organization',
            'name' => $event->host(),
            'url' => config('app.url'),
        ]);

        if ($image = $event->imageUrl()) {
            SEO::jsonLdMulti()->addImage($image);
        }
    }

    /**
     * Sets the default SEO when viewing the docs page.
     *
     * @param  string  $page
     * @param  string|null  $version
     * @param  string|null  $section
     * @return void
     */
    public function setSeoViewingDocs($page, $version = null, $section = null)
    {
        $docTitle = str($page)->headline();
        if ($docTitle->startsWith('Ny')) {
            $docTitle = $docTitle->replace(' ', '');
        }

        SEO::setTitle($docTitle.' - '.config('app.name').' - Flutter Micro-framework');
        SEO::setDescription($docTitle.' documentation for '.config('app.name').'. Build modern applications on top of the foundation '.config('app.name').' provides from it\'s micro-framework for Flutter.');
        SEO::opengraph()->addProperty('type', 'article');
        SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));

        // TechArticle structured data
        SEO::jsonLd()->setType('TechArticle');
        SEO::jsonLd()->setTitle($docTitle.' - '.config('app.name').' Documentation');
        SEO::jsonLd()->setDescription($docTitle.' documentation for '.config('app.name').'. Build modern applications on top of the foundation '.config('app.name').' provides from it\'s micro-framework for Flutter.');
        SEO::jsonLd()->addValue('headline', $docTitle);
        SEO::jsonLd()->addValue('dateModified', now()->toIso8601String());
        SEO::jsonLd()->addValue('author', [
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('images/nylo_logo_filled.png'),
            ],
        ]);

        // Add breadcrumb navigation
        if ($version && $section) {
            SEO::jsonLd()->addValue('breadcrumb', [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => config('app.url'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Documentation',
                        'item' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $version]),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => str($section)->headline(),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 4,
                        'name' => $docTitle,
                        'item' => route('landing.docs', ['locale' => app()->getLocale(), 'version' => $version, 'page' => $page]),
                    ],
                ],
            ]);
        }

        // Add SoftwareApplication context for the framework
        SEO::jsonLd()->addValue('about', [
            '@type' => 'SoftwareApplication',
            'name' => config('app.name'),
            'applicationCategory' => 'DeveloperApplication',
            'operatingSystem' => 'Cross-platform',
            'softwareVersion' => $version ?? 'latest',
            'description' => 'Nylo is an open-source micro-framework for Flutter that makes building apps a breeze.',
            'applicationSubCategory' => 'Framework',
        ]);
    }
}
