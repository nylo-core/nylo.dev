<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
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

	public function download(HttpRequest $request, $project = 'nylo-core/nylo')
	{
		$downloadUrl = $this->docService->downloadFile($project);

		return redirect($downloadUrl);
	}

	public function index()
	{
		$this->seoService->setTitle(config('app.name') . ' - Powerful Flutter Micro-Framework');

		return view('pages.index');
	}

	public function contributions()
	{
		$this->seoService->setTitle('Contributions');

		return view('pages.contributions');
	}

	public function privacyPolicy()
	{
		$this->seoService->setTitle('Privacy policy');

		return view('pages.privacy-policy');
	}

	public function termsAndConditions()
	{
		$this->seoService->setTitle('Terms and conditions');

		return view('pages.terms-and-conditions');
	}

	public function resources()
	{
		$this->seoService->setTitle('Resources');

		return view('pages.resources');
	}

	public function viewDocs(HttpRequest $request, $version = '4.x', $page = 'installation')
	{
		$this->seoService->setSeoViewingDocs($page);

		$mdDocPage = $this->docService->checkIfDocExists($version, $page);

		$section = $this->docService->findDocSection($version, $page);
		$viewingOldDocs = $this->docService->isViewingOldDocs($version);
		$latestVersionOfNylo = $this->docService->getLastestVersionNylo();
		$docsContainPage = $this->docService->checkDocsContainPage($version, $page);

		return view('docs.template', compact('page', 'version', 'mdDocPage', 'section', 'latestVersionOfNylo', 'viewingOldDocs', 'docsContainPage'));
	}
}