<?php

namespace App\Http\Controllers;

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
		$this->setDefaultSeo();
	}

	public function setDefaultSeo()
	{
		SEO::setDescription(config('app.name') . ' is an open-source micro-framework for Flutter that makes building apps a breeze. It provides all the basic building blocks to create a modern application.');
		SEO::setCanonical(Request::url());
		SEO::opengraph()->setUrl(Request::url());
		SEO::twitter()->setSite('@nylo_dev');
		SEO::opengraph()->addProperty('type', 'website');
        SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));
	}

	public function download(HttpRequest $request)
	{
		$response = Http::get("https://api.github.com/repos/nylo-core/nylo/releases/latest");
		abort_if(!$response->successful(), 500);

		$download = Download::create([
			'project' => 'nylo-core/nylo',
			'version' => $response->json('name'),
			'ip' => $request->ip()
		]);
		abort_if(!$download, 500);		

		$zipballUrl = $response->json('zipball_url');

		return redirect($zipballUrl);
	}

	public function index()
	{
		SEO::setTitle(config('app.name') . ' - Powerful Flutter Micro-Framework | Nylo');

		return view('pages.index');
	}

	public function contributions()
	{
		SEO::setTitle('Contributions | ' . config('app.name'));

		return view('pages.contributions');
	}

	public function privacyPolicy()
	{
		SEO::setTitle('Privacy policy | ' . config('app.name'));

		return view('pages.privacy-policy');
	}

	public function termsAndConditions()
	{
		SEO::setTitle('Terms and conditions | ' . config('app.name'));

		return view('pages.terms-and-conditions');
	}

	public function resources()
	{
		SEO::setTitle('Resources | ' . config('app.name'));

		return view('pages.resources');
	}

	public function viewDocs($version = '3.x', $page = 'installation')
	{
		SEO::setTitle(str($page)->headline() . ' - ' . config('app.name') . ' - Flutter Micro-framework');
		SEO::setDescription(str($page)->headline() . ' documentation for Nylo. Build modern applications on top of the foundation ' . config('app.name') . ' provides from it\'s micro-framework for Flutter.');
		SEO::opengraph()->addProperty('type', 'articles');
		SEO::jsonLd()->addImage(asset('images/nylo-social-banner-github.png'));

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