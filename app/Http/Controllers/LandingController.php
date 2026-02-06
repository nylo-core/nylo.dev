<?php

namespace App\Http\Controllers;

use App\Http\Services\DocService;
use App\Http\Services\PackageService;
use App\Http\Services\SeoService;
use App\Models\OnlineEvent;
use Illuminate\Support\Str;

/**
 * Class LandingController
 *
 * @property SeoService $seoService
 * @property DocService $docService
 * @property PackageService $packageService
 */
class LandingController extends Controller
{
    public function __construct(
        SeoService $seoService,
        DocService $docService,
        PackageService $packageService
    ) {
        $this->seoService = $seoService;
        $this->docService = $docService;
        $this->packageService = $packageService;
    }

    /**
     * Download the latest version of Nylo.
     *
     * @param  string  $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function download($project = 'nylo-core/nylo')
    {
        $downloadUrl = $this->docService->downloadFile($project);

        return redirect($downloadUrl);
    }

    /**
     * Index page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->seoService->setTitle(config('app.name').' - Powerful Flutter Micro-Framework');

        $event = cache()->remember('event', now()->addHours(1), function () {
            $events = OnlineEvent::where('end_date', '>', now())->get();
            if (empty($events)) {
                return null;
            }

            return $events->first();
        });

        return view('pages.index', compact('event'));
    }

    /**
     * Privacy policy page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function privacyPolicy()
    {
        $this->seoService->setTitle(__('Privacy policy'));

        $content = $this->loadLegalMarkdown('privacy-policy');

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

        $content = $this->loadLegalMarkdown('terms-and-conditions');

        return view('pages.terms-and-conditions', compact('content'));
    }

    /**
     * Load a legal markdown document based on current locale.
     */
    protected function loadLegalMarkdown(string $document): string
    {
        $locale = app()->getLocale();
        $path = resource_path("legal/{$locale}/{$document}.md");

        if (! file_exists($path)) {
            $path = resource_path("legal/en/{$document}.md");
        }

        return Str::markdown(file_get_contents($path));
    }

    /**
     * Tutorials page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function tutorials($version = null, $page = 'introduction')
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function docs(?string $locale = null, $version = null, $page = 'installation')
    {
        $latestVersionOfNylo = $this->docService->getLastestVersionNylo();

        if ($version == null) {
            $version = $latestVersionOfNylo;
        }

        $resolvedLocale = app()->getLocale();
        $mdDocPage = $this->docService->checkIfDocExists($version, $page, $resolvedLocale);

        $section = $this->docService->findDocSection($version, $page);
        $viewingOldDocs = $this->docService->isViewingOldDocs($version);
        $docsContainPage = $this->docService->checkDocsContainPage($version, $page);

        // Set SEO for viewing docs
        $this->seoService->setSeoViewingDocs($page, $version, $section);

        // Generate the doc contents and on-this-page array
        $docContents = $this->docService->generateDocPage($mdDocPage, $version);

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
