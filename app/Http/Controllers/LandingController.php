<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use SEO;
use Request;
use App\Download;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class LandingController extends Controller
{
	public function __construct()
	{
		SEOTools::setDescription(config('app.name') . ' is an open-source app for Flutter that makes building apps simpler, faster and cleaner. It provides the foundation to start creating stright away. View our documentation to lean how you can take advantage of the framework.');
		SEOTools::setCanonical(Request::url());
		SEOTools::opengraph()->setUrl(Request::url());
		SEOTools::twitter()->setSite('@nylo_dev');
		SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));
	}

	public function download(HttpRequest $request)
	{
		$latestNyloVersion = config('project.meta.repos.nylo.version');

		$download = Download::create([
			'project' => 'http://github.com/nylo-core/nylo',
			'version' => $latestNyloVersion,
			'ip' => $request->ip()
		]);

		abort_if(!$download, 500);

		$response = Http::get("https://api.github.com/repos/nylo-core/nylo/releases/latest");

		abort_if(!$response->successful(), 500);

		$zipballUrl = $response->json('zipball_url');

		return redirect($zipballUrl);
	}

	public function index()
	{
		SEOTools::setTitle(config('app.name') . ' - Powerful Flutter micro-framework');

		return view('pages.index');
	}

	public function contributions()
	{
		SEOTools::setTitle('Contributions - ' . config('app.name'));

		return view('pages.contributions');
	}

	public function privacyPolicy()
	{
		SEOTools::setTitle('Privacy policy - ' . config('app.name'));

		return view('pages.privacy-policy');
	}

	public function termsAndConditions()
	{
		SEOTools::setTitle('Terms and conditions - ' . config('app.name'));

		return view('pages.terms-and-conditions');
	}

	public function resources()
	{
		SEOTools::setTitle('Resources - ' . config('app.name'));

		return view('pages.resources');
	}

	public function viewDocs($version = '3.x', $page = 'installation')
	{
		SEO::setTitle(str($page)->headline() . ' - ' . config('app.name') . ' - Flutter Micro-framework');
		SEO::setDescription('Documentation for Nylo, a Flutter Micro-framework.');
		SEO::opengraph()->addProperty('type', 'articles');
		SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));

		// docs,flutter,nylo,dart,app,development,framework

		$mdDocPage = base_path() . '/resources/docs/' . $version . '/' . $page . '.md';
		abort_if(file_exists($mdDocPage) == false, 404);

		$section = '';
		foreach (config('project.doc-index')['versions']['3.x'] as $key => $docLink) {
			if (in_array($page, $docLink)) {
				$section = $key;
				break;
			}
		}

		$viewingOldDocs = false;
		$latestVersionOfNylo = array_key_last(config('project.doc-index')['versions']);

		if ($latestVersionOfNylo != $version) {
			$viewingOldDocs = true;
		}

		return view('docs.template', compact('page', 'version', 'mdDocPage', 'section', 'latestVersionOfNylo', 'viewingOldDocs'));
	}
}