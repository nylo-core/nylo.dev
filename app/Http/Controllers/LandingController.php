<?php

namespace App\Http\Controllers;

use App\Http\Services\DocService;
use App\Http\Services\SeoService;

/**
 * Class LandingController
 *
 * @property SeoService $seoService
 * @property DocService $docService
 * @package App\Domain\Landing
 */
class LandingController extends Controller
{
	public function __construct(SeoService $seoService, DocService $docService)
	{
		$this->seoService = $seoService;
		$this->docService = $docService;
	}

    /**
     * Download the latest version of Nylo.
     *
     * @return Response
     */
	public function download($project = 'nylo-core/nylo')
	{
		$downloadUrl = $this->docService->downloadFile($project);

		return redirect($downloadUrl);
	}

    /**
     * Index page for Nylo.
     *
     * @return Response
     */
	public function index()
	{
		$this->seoService->setTitle(config('app.name') . ' - Powerful Flutter Micro-Framework');

		return view('pages.index');
	}

    /**
     * Privacy policy page for Nylo.
     *
     * @return Response
     */
	public function privacyPolicy()
	{
		$this->seoService->setTitle('Privacy policy');

		return view('pages.privacy-policy');
	}

    /**
     * Terms and conditions page for Nylo.
     *
     * @return Response
     */
	public function termsAndConditions()
	{
		$this->seoService->setTitle('Terms and conditions');

		return view('pages.terms-and-conditions');
	}

    /**
     * Resources page for Nylo.
     *
     * @return Response
     */
	public function resources()
	{
		$this->seoService->setTitle('Resources');

		return view('pages.resources');
	}

    /**
     * Documentation page for Nylo.
     *
     * @return Response
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
