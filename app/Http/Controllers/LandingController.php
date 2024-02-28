<?php

namespace App\Http\Controllers;

use App\Http\Services\DocService;
use App\Http\Services\SeoService;
use App\Http\Services\PackageService;
use App\Models\OnlineEvent;

/**
 * Class LandingController
 *
 * @property SeoService $seoService
 * @property DocService $docService
 * @property PackageService $packageService
 * @package App\Http\Controllers\LandingController
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
     * @param string $project
     *
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
		$this->seoService->setTitle(config('app.name') . ' - Powerful Flutter Micro-Framework');

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
		$this->seoService->setTitle('Privacy policy');

		return view('pages.privacy-policy');
	}

    /**
     * Terms and conditions page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function termsAndConditions()
	{
		$this->seoService->setTitle('Terms and conditions');

		return view('pages.terms-and-conditions');
	}

    /**
     * Resources page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function resources()
	{
		$this->seoService->setTitle('Resources');
		$resourceData = $this->packageService->getResourceMetaData();

		return view('pages.resources', compact('resourceData'));
	}

	/**
     * Ecosystem page for Nylo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function ecosystem()
	{
		$this->seoService->setTitle('Ecosystem');
        $resourceData = $this->packageService->getResourceMetaData();

		return view('pages.ecosystem', compact('resourceData'));
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
     * @param string $version
     * @param string $page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function docs($version = null, $page = 'installation')
	{
		$this->seoService->setSeoViewingDocs($page);
		$latestVersionOfNylo = $this->docService->getLastestVersionNylo();

		if ($version == null) {
			$version = $latestVersionOfNylo;
		}

		$mdDocPage = $this->docService->checkIfDocExists($version, $page);

		$section = $this->docService->findDocSection($version, $page);
		$viewingOldDocs = $this->docService->isViewingOldDocs($version);
		$docsContainPage = $this->docService->checkDocsContainPage($version, $page);

		return view('docs.template', compact('page', 'version', 'mdDocPage', 'section', 'latestVersionOfNylo', 'viewingOldDocs', 'docsContainPage'));
	}
}
