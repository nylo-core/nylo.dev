@extends('layouts.app-landing')

@section('content')

<section class="section bg-light">
	<div class="container">

		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>Useful Resources</h1>
				<p class="title-alt">We'll add resources here that we think find you'll find useful.</p>
			</div>
		</div>

		<div class="row justify-content-center">
			
			<div class="col-md-7 border-top pt-4 mt-2">
					<p class="h5"><i class="ri-book-fill"></i> Docs</p>
					<p>Our official documentation for {{ config('app.name') }}</p>
					<p><a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'installation']) }}">View documentation</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<p class="h5"><i class="ri-smartphone-fill"></i> {{ config('app.name') }}</p>
					<p>This repository is for those looking to build an app using {{ config('app.name') }}, it contains the default setup for new projects.</p>
					<p>{{ config('project.meta.repos.nylo.version') }} <a href="{{ config('project.meta.repos.nylo.repo_name') }}/releases/tag/{{ config('project.meta.repos.nylo.version') }}">Release notes</a> | <a href="{{ route('landing.download') }}">Download</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<p class="h5"><i class="ri-code-box-fill"></i> Framework</p>
					<p>Our framework package contains the beefy code to run Flutter projects.</p>
					<p>{{ config('project.meta.repos.framework.version') }} <a href="{{ config('project.meta.repos.framework.repo_name') }}/releases/tag/{{ config('project.meta.repos.framework.version') }}">Release notes</a> | <a href="{{ config('project.meta.repos.framework.repo_name') }}">View repository</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<p class="h5"><i class="ri-code-box-fill"></i> Support</p>
					<p>The support library is used by the framework to add support classes in your Flutter projects.</p>
					<p>{{ config('project.meta.repos.support.version') }} <a href="{{ config('project.meta.repos.support.repo_name') }}/releases/tag/{{ config('project.meta.repos.support.version') }}">Release notes</a> | <a href="{{ config('project.meta.repos.support.repo_name') }}">View repository</a></p>
				</div>

				<div class="col-md-7 border-top pt-4 mt-2">
					<p class="h5"><i class="ri-inbox-unarchive-fill"></i> Flutter Packages</p>
					<p>All our public Flutter packages available.</p>
					<p><a href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK">View Packages</a></p>
				</div>
			
		</div>
	</div>
</section>


<div class="position-relative">
	<div class="shape overflow-hidden text-footer">
		<svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="#343a40"></path>
		</svg>
	</div>
</div>
@endsection