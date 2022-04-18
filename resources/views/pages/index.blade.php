@extends('layouts.app-landing')

@section('content')

<section class="section home" id="home">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="home-wrapper text-left">
					<div class="text-tran-box">
						<h1 class="text-transparent">Powerful <br>micro-framework <br>for Flutter.</h1>
					</div>
					<p>Developer tools for everyone to build Flutter apps more elegantly. <br>{{ config('app.name') }} is a micro-framework designed to help structure your projects and provide tools that will make developing apps simpler.</p>
				</div>
			</div>
			<div class="col-md-5 text-center align-self-center">
				<img class="img-fluid" style="height: 285px;" src="{{ asset('images/hero_animation.gif') }}">
			</div>
		</div>
		<div class="row justify-content-center mt-4">
			<div class="col-md-11 text-left">
				<a href="{{ route('landing.download') }}" class="btn btn-outline-primary mr-2"><i class="ri-download-line align-middle"></i> Download</a>
				<a href="{{ route('larecipe.index') }}" class="btn btn-primary ml-2"><i class="ri-book-open-line align-middle"></i> Documentation</a>
				<a href="{{ config('project.meta.repos.nylo.repo_name') }}" class="btn btn-muted ml-2"><i class="ri-github-line align-middle"></i> GitHub</a>
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

<section class="section" id="features">
	<div class="container">

		<div class="row mt-4">

			<div class="col-sm-4">
				<div class="features-box text-center">
					<div class="feature-icon">
						<i class="ri-stack-line"></i>
					</div>
					<h3>Faster Development</h3>

					<p class="text-muted">If you already know Flutter you'll be able to start building your apps straight away with little configuration.</p>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="features-box text-center">
					<div class="feature-icon">
						<i class="ri-folder-open-line"></i>
					</div>
					<h3>Clean structure</h3>

					<p class="text-muted">We provide the basics setup like the router file, directory setup and themes so you can focus on app development.</p>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="features-box text-center">
					<div class="feature-icon">
						<i class="ri-pencil-ruler-2-line"></i>
					</div>
					<h3>Still Flutter</h3>

					<p class="text-muted">{{ config('app.name') }} was built for Flutter developers and the community to advance app developers tools to build amazing projects.</p>
				</div>
			</div>
		</div>

	</div>
</section>


<section class="section bg-light" id="pricing">
	<div class="container">

		<div class="row">
			<div class="col-sm-12 text-center">
				<h2 class="title">Packages released</h2>
				<p class="title-alt">The {{ config('app.name') }} framework is open sourced and MIT-licenced, we welcome any <a href="{{ route('landing.contributions') }}">contributions</a>.</p>
			</div>
		</div>

		<div class="row justify-content-center">
			
				<div class="col-md-7">
					<p class="h5"><i class="ri-smartphone-fill"></i> {{ config('app.name') }}</p>
					<p>This repository is for those looking to build an app using {{ config('app.name') }}, it contains the default setup for new projects.</p>
					<p>{{ config('project.meta.repos.nylo.version') }} <a target="_BLANK" href="{{ config('project.meta.repos.nylo.repo_name') }}/releases/tag/{{ config('project.meta.repos.nylo.version') }}" target="_BLANK">Release notes</a> | <a href="{{ route('landing.download') }}">Download</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<p class="h5"><i class="ri-code-box-fill"></i> Framework</p>
					<p>Our framework package contains the beefy code to run Flutter projects.</p>
					<p>{{ config('project.meta.repos.framework.version') }} <a href="{{ config('project.meta.repos.framework.repo_name') }}/releases/tag/{{ config('project.meta.repos.framework.version') }}" target="_BLANK">Release notes</a> | <a href="{{ config('project.meta.repos.framework.repo_name') }}">View repository</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<p class="h5"><i class="ri-code-box-fill"></i> Support</p>
					<p>The support library is used by the framework to add support classes in your Flutter projects.</p>
					<p>{{ config('project.meta.repos.support.version') }} <a href="{{ config('project.meta.repos.support.repo_name') }}/releases/tag/{{ config('project.meta.repos.support.version') }}" target="_BLANK">Release notes</a> | <a href="{{ config('project.meta.repos.support.repo_name') }}">View repository</a></p>
				</div>
			
		</div>
	</div>
</section>

<section class="section" id="faqs">
	<div class="container">
		<div class="row text-center">
			<div class="col-sm-12">
				<h2 class="title">Some quirks and features</h2>
				<p class="title-alt">We're sure you'll enjoy using some of the tools {{ config('app.name') }} has...</p>

				<div class="row text-left">
					<div class="col-sm-6">
						<div class="question-box">
							<h4>Route management</h4>
							<p>{{ config('app.name') }} provides a simple router file where you can add all your routes. It's very customizable and doesn't require any fiddling in the main.dart file.</p>
						</div>

						<div class="question-box">
							<h4>Configuration</h4>
							<p>We include a .env file and .env-example file where you can add your configuration variables for the project. Fonts, themes are also easy to modify, default projects include Google Fonts so you can choose a wide range of fonts.</p>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="question-box">
							<h4>Widgets</h4>
							<p>We've organized the location for your widgets and pages in the new <code>resources</code> directory. You'll be able to keep track of things better.</p>
						</div>
						
						<div class="question-box">
							<h4>App icons</h4>
							<p>Build all your app icons quicker for your project by running <code>flutter pub run flutter_launcher_icons:main</code>.</p>
						</div>

					</div>

				</div>
			</div> <!-- end Col -->
		</div>
	</div>
</section>


<!-- Client section start -->
<section class="section bg-light" id="clients">
	<div class="container">
		<div class="row text-center">
			<div class="col-sm-12">
				<h5 class="title h2">Want to understand more?</h5>
				<a href="{{ $docsIndex }}" class="btn btn-primary mt-2"><i class="ri-book-open-line align-middle"></i> Read the documentation</a>
			</div> <!-- end Col -->
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