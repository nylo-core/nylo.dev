<?php

namespace App\Http\Services;

use SEO;
use Request;

/**
 * Class SeoService
 *
 * @package App\Http\Services\SeoService
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
    	SEO::setDescription(config('app.name') . ' is an open-source micro-framework for Flutter that makes building apps a breeze. It provides all the basic building blocks to create a modern application.');
    	SEO::setCanonical(Request::url());
    	SEO::opengraph()->setUrl(Request::url());
    	SEO::twitter()->setSite('@nylo_dev');
    	SEO::opengraph()->addProperty('type', 'website');
    	SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));
    }

    /**
     * Sets the SEO title.
     *
     * @param String $title
     * @return void
     */
    public function setTitle($title)
    {
    	SEO::setTitle($title . ' | ' . config('app.name'));
    }

    /**
     * Sets the default SEO when viewing the docs page.
     *
     * @param String $page
     * @return void
     */
    public function setSeoViewingDocs($page)
    {
    	SEO::setTitle(str($page)->headline() . ' - ' . config('app.name') . ' - Flutter Micro-framework');
		SEO::setDescription(str($page)->headline() . ' documentation for ' . config('app.name') . '. Build modern applications on top of the foundation ' . config('app.name') . ' provides from it\'s micro-framework for Flutter.');
		SEO::opengraph()->addProperty('type', 'articles');
		SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));
    }

}