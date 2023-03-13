@extends('layouts.app-landing')

@section('content')

<section class="section home" id="home">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-10 flex justify-content-center text-center">
				<div class="home-wrapper">
					<div class="text-tran-box">
						<h1 class="text-center text-transparent">The Powerful<br>Micro-Framework for Flutter</h1>
					</div>
					<p>Developer tools to build Flutter apps elegantly.</p>
				</div>
			</div>
		</div>
		<div id="header-cta-links" class="row justify-content-center mt-4">
			<div class="col-sm-12 col-md-3 text-center">
				<a href="{{ route('landing.download') }}" class="block btn-block btn btn-outline-primary"><i class="ri-download-line align-middle"></i> Download</a>
			</div>
			<div class="col-sm-12 col-md-3 text-center">
				<a href="/docs" class="block btn-block btn btn-primary"><i class="ri-book-open-line align-middle"></i> Documentation</a>
			</div>
			<div class="col-sm-12 col-md-3 text-center">
				<a href="{{ config('project.meta.repos.nylo.repo_name') }}" target="_BLANK" class="block btn-block btn btn-muted"><i class="ri-github-line align-middle"></i> GitHub</a>
			</div>
		</div>
	</div>
</section>

<div class="position-relative">
	<div class="shape overflow-hidden text-white">
		<svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
		</svg>
	</div>
</div>

<section class="section">
	<div class="container">

		<div class="row mt-4">

			<div class="col-sm-4">
				<div class="features-box text-center">
					<div class="feature-icon">
						<i class="ri-stack-line"></i>
					</div>
					<h3>Faster Development</h3>

					<p class="text-muted">{{ config('app.name') }} builds on top of Flutter to provide extra utility, structure and configuration. Download the project and hit build to start creating.</p>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="features-box text-center">
					<div class="feature-icon">
						<i class="ri-folder-open-line"></i>
					</div>
					<h3>Clean structure</h3>

					<p class="text-muted">Streamline your Flutter projects with {{ config('app.name') }}'s directory structure. Every project comes with a boilerplate, default files and CLI tool to create things.</p>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="features-box text-center">
					<div class="feature-icon">
						<i class="ri-pencil-ruler-2-line"></i>
					</div>
					<h3>Still Flutter</h3>

					<p class="text-muted">The features have been packed into Nylo's compact framework so you can use all your usual tools on top of the package to build amazing projects.</p>
				</div>
			</div>
		</div>

	</div>
</section>


<section class="section bg-light">
	<div class="container">

		<div class="row">
			<div class="col-sm-12 text-center">
				<h2 class="title">Packages</h2>
				<p class="title-alt">The {{ config('app.name') }} framework is open sourced and MIT-licenced, we welcome any <a href="{{ route('landing.contributions') }}">contributions</a>.</p>
			</div>
		</div>

		<div class="row justify-content-center">
			
				<div class="col-md-7">
					<div class="flex flex-wrap"><i class="ri-smartphone-fill mr-1"></i><p class="h5">{{ config('app.name') }}</p></div>
					<p>This repository is for those looking to build an app using {{ config('app.name') }}.</p>
					<p>{{ config('project.meta.repos.nylo.version') }} <a target="_BLANK" href="{{ config('project.meta.repos.nylo.repo_name') }}/releases/tag/{{ config('project.meta.repos.nylo.version') }}" target="_BLANK">Release notes</a> | <a href="{{ route('landing.download') }}">Download</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<div class="flex flex-wrap"><i class="ri-code-box-fill mr-1"></i> <p class="h5">Framework</p></div>
					<p>The framework package is used by the {{ config('app.name') }} project.</p>
					<p>{{ config('project.meta.repos.framework.version') }} <a href="{{ config('project.meta.repos.framework.repo_name') }}/releases/tag/{{ config('project.meta.repos.framework.version') }}" target="_BLANK">Release notes</a> | <a href="{{ config('project.meta.repos.framework.repo_name') }}">View repository</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<div class="flex flex-wrap"><i class="ri-code-box-fill mr-1"></i> <p class="h5">Support</p></div>
					<p>This package helps power the {{ config('app.name') }} framework.</p>
					<p>{{ config('project.meta.repos.support.version') }} <a href="{{ config('project.meta.repos.support.repo_name') }}/releases/tag/{{ config('project.meta.repos.support.version') }}" target="_BLANK">Release notes</a> | <a href="{{ config('project.meta.repos.support.repo_name') }}">View repository</a></p>
				</div>
			
		</div>
	</div>
</section>

<section class="section bae-blue">
	<div class="container">
		<div class="row text-center">
			<div class="col-10 m-auto">
				<h3 class="h2">Some Quirks &amp; Features</h3>
				<p class="title-alt">Here are some of the tools that {{ config('app.name') }} has under the hood...</p>

				<div class="row text-left">
					<div class="col-sm-6">
						<div class="question-box">
							<h4 class="qandf-text-h">Routing</h4>
							<p>{{ config('app.name') }} provides a router file where you can add all your routes. It's very customizable and doesn't require any fiddling in the main.dart file.</p>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="question-box">
							<h4 class="qandf-text-h">Networking</h4>
							<p>We include a slick networking class that makes writing API Services a breeze. Model decoders make it easy to parse your JSON payloads into your models.</p>
						</div>
					</div>
				</div>
				<div class="row text-left mt-4">
					<div class="col-sm-6">
						<div class="question-box">
							<h4 class="qandf-text-h">Let's Create</h4>
							<p>Metro is a CLI tool that allows you to create models, pages, widgets, API services and more. Try running <code>flutter pub run nylo_framework:main</code> in your project.</p>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="question-box">
							<h4 class="qandf-text-h">Storage</h4>
							<p>Secure storage out of the box! Nylo comes with two classes for storage, NyStorage and a lightweight Backpack class for quick access to data on the fly.</p>
						</div>
					</div>
				</div>

				<div class="row text-left mt-4">
					<div class="col-sm-6">
						<div class="question-box">
							<h4 class="qandf-text-h">Configuration</h4>
							<p>Manage your global configuration from a .env file. You can also easily update your font, themes and localization.</p>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="question-box">
							<h4 class="qandf-text-h">App Icons</h4>
							<p>Build all your app icons quicker for your project by running <code>flutter pub run flutter_launcher_icons:main</code>.</p>
						</div>
					</div>
				</div>

			</div> <!-- end Col -->
		</div>
	</div>
</section>


<!-- Client section start -->
<section class="section bg-light bottom-grad">
	<div class="container">
		<div class="row text-center">
			<div class="col-sm-12">
				<h5 class="title h2">Get started, learn {{ config('app.name') }}</h5>
				<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'installation']) }}" class="btn btn-primary mt-2"><i class="ri-book-open-line align-middle"></i> Get Started</a>
			</div> 
		</div>
		<!-- end row -->
	</div>
	<!-- end container -->
</section>
<!-- Client section end -->

<div class="position-relative">
	<div class="shape overflow-hidden text-footer">
		<svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="#343a40"></path>
		</svg>
	</div>
</div>
@endsection