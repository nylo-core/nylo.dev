<?php

namespace App\Http\Services;

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
     *
     * @return void
     */
    public function setDefaultSeo()
    {
        SEO::setDescription(config('app.name').' is an open-source micro-framework for Flutter that makes building apps a breeze. It provides all the basic building blocks to create a modern application.');
        SEO::setCanonical(Request::url());
        SEO::opengraph()->setUrl(Request::url());
        SEO::twitter()->setSite('@nylo_dev');
        SEO::opengraph()->addProperty('type', 'website');
        SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));
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
