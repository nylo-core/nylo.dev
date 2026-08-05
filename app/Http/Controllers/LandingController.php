<?php

namespace App\Http\Controllers;

use App\Http\Services\DocService;
use App\Http\Services\LandingContentService;
use App\Http\Services\SeoService;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function __construct(
        private readonly SeoService $seoService,
        private readonly DocService $docService,
        private readonly LandingContentService $landingContent
    ) {}

    /**
     * Index page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->seoService->setTitle(config('app.name').' - Powerful Flutter Micro-Framework');

        $version = $this->docService->getLastestVersionNylo();

        return view('pages.index', [
            'event' => $this->landingContent->currentEvent(),
            'installCommand' => 'dart pub global activate nylo_installer',
            'docsUrl' => $this->landingContent->docsUrls($version),
            'pillars' => $this->landingContent->pillars($version),
            'extraFeatures' => $this->landingContent->extraFeatures($version),
            'metroHighlights' => $this->landingContent->metroHighlights(),
            'explorerTabs' => $this->landingContent->explorerTabs(),
            'testimonials' => $this->landingContent->testimonials(),
        ]);
    }

    /**
     * Privacy policy page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function privacyPolicy()
    {
        $this->seoService->setTitle(__('Privacy policy'));

        $content = $this->docService->loadLegalMarkdown('privacy-policy');

        return view('pages.privacy-policy', compact('content'));
    }

    /**
     * Terms and conditions page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function termsAndConditions()
    {
        $this->seoService->setTitle(__('Terms and conditions'));

        $content = $this->docService->loadLegalMarkdown('terms-and-conditions');

        return view('pages.terms-and-conditions', compact('content'));
    }

    /**
     * Tutorials page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function tutorials(?string $version = null, string $page = 'introduction')
    {
        $this->seoService->setTitle('Tutorials');

        $latestVersionOfNylo = $this->docService->getLastestVersionNylo();

        if ($version == null) {
            $version = $latestVersionOfNylo;
        }
        $this->docService->containsTutorialsForVersion($version);
        $section = $this->docService->findTutorialSection($version, $page);
        $viewingOldDocs = $this->docService->isViewingOldDocs($version);
        $tutorial = $this->docService->getTutorial($version, $page);
        abort_if(empty($tutorial), 404);

        return view('docs.tutorials', compact('page', 'tutorial', 'version', 'section', 'latestVersionOfNylo', 'viewingOldDocs'));
    }

    /**
     * Documentation page for Nylo.
     *
     * @param  string  $version
     * @param  string  $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function docs(?string $locale = null, $version = null, $page = 'installation')
    {
        $latestVersionOfNylo = $this->docService->getLastestVersionNylo();

        if ($version == null) {
            $version = $latestVersionOfNylo;
        }

        $resolvedLocale = app()->getLocale();
        $mdDocPage = $this->docService->checkIfDocExists($version, $page, $resolvedLocale);

        // Generate the doc contents and on-this-page array
        $docContents = $this->docService->generateDocPage($mdDocPage, $version);

        // Return raw markdown for LLM requests
        if (request()->attributes->get('wants_markdown')) {
            return response($docContents['rawMarkdown'], 200, [
                'Content-Type' => 'text/markdown; charset=UTF-8',
            ]);
        }

        $section = $this->docService->findDocSection($version, $page);
        $viewingOldDocs = $this->docService->isViewingOldDocs($version);
        $docsContainPage = $this->docService->checkDocsContainPage($version, $page);

        // Set SEO for viewing docs
        $this->seoService->setSeoViewingDocs($page, $version, $section);

        return view('docs.template', compact(
            'page',
            'version',
            'mdDocPage',
            'section',
            'latestVersionOfNylo',
            'viewingOldDocs',
            'docsContainPage',
            'docContents'
        ));
    }

    /**
     * API Documentation page for Nylo.
     *
     * @param  string  $version
     * @param  string  $page
     */
    public function apiDocs($version = null, $page = 'introduction'): string
    {
        $latestVersionOfNylo = $this->docService->getLastestVersionNylo();

        if ($version == null) {
            $version = $latestVersionOfNylo;
        }

        $mdDocPage = $this->docService->checkIfDocExists($version, $page, 'en');

        // Generate the doc contents and on-this-page array
        $docContents = $this->docService->generateDocPage($mdDocPage, $version);

        $mdContents = $docContents['contents'];

        return Str::markdown($mdContents, [
            'html_input' => 'strip',
        ]);
    }

    /**
     * Learn more page for Nylo v7.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function learnMoreV7()
    {
        $this->seoService->setTitle('Learn more - Nylo v7');

        return view('pages.learn-more-v7');
    }

    /**
     * Resources page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function resources()
    {
        $this->seoService->setTitle('Resources - Nylo');

        $latestVersionOfNylo = $this->docService->getLastestVersionNylo();

        return view('pages.resources', compact('latestVersionOfNylo'));
    }
}
