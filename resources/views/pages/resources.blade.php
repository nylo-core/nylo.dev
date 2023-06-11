@extends('layouts.app-landing')

@section('content')

<section class="pt-[80px] px-7 md:px-0">
	<h1 class="text-5xl text-center sora mb-2">Useful <span class="text-primary-blue-deep sora font-semibold">Resources</span></h1>

	<h2 class="text-center mb-10 text-2xl">Resources to help you build with {{ config('app.name') }}.</h2>
</section>

<section class="mx-auto">

	<div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-7 md:px-0 max-w-[806px] mx-auto">
		
		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
			<img src="{{ asset('images/phone.png') }}" class="mb-6 h-[50px]" alt="Create a {{ config('app.name') }} app">
				<span class="font-semibold text-[20px]">{{ config('app.name') }}</span>

				<span class="bg-[#ECF5FC] ml-2 px-2 text-primary-blue-deep rounded-3xl py-1 font-semibold">v{{ $resourceData['nylo']['version'] }}</span>
				
				<p class="text-[18px] text-[#81888E] mb-2 mt-2" style="letter-spacing: -0.02em;">This repository is for those looking to build an app using {{ config('app.name') }}, it contains the default setup for new projects.</p>

				<div class="flex flex-wrap">
					
					<a href="{{ $resourceData['nylo']['release_note_url'] }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Release Notes <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>

				<img src="{{ asset('images/rectangle.png') }}" class="w-0.5 mx-2 h-5 m-auto self-center">

				<a href="{{ route('landing.download') }}" class="inline-flex self-center text-[#6C7379]">
					Download <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
				</div>
		</div>


		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
			<img src="{{ asset('images/framework.png') }}" class="mb-6 h-[50px]" alt="{{ config('app.name') }} framework library">
				<span class="font-semibold text-[20px]">Framework</span>

				<span class="bg-[#ECF5FC] ml-2 px-2 text-primary-blue-deep rounded-3xl py-1 font-semibold">{{ $resourceData['framework']['version'] }}</span>
				
				<p class="text-[18px] text-[#81888E] mb-2 mt-2" style="letter-spacing: -0.02em;">Our framework package contains all the essential libraries for running a Flutter project using {{ config('app.name') }}.</p>

				<div class="flex flex-wrap">
					<a href="https://github.com/{{ $resourceData['framework']['organization'] }}/{{ $resourceData['framework']['repository'] }}/releases/tag/{{ $resourceData['framework']['version'] }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Release Notes <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>

				<img src="{{ asset('images/rectangle.png') }}" class="w-0.5 mx-2 h-5 m-auto self-center">

				<a href="{{ 'https://github.com/' . $resourceData['framework']['organization'] . '/' . $resourceData['framework']['repository'] }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Repository <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
				</div>
		</div>

		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
			<img src="{{ asset('images/support.png') }}" class="mb-6 h-[50px]" alt="{{ config('app.name') }} support library">
				<span class="font-semibold text-[20px]">Support</span>

				<span class="bg-[#ECF5FC] ml-2 px-2 text-primary-blue-deep rounded-3xl py-1 font-semibold">{{ $resourceData['support']['version'] }}</span>
				
				<p class="text-[18px] text-[#81888E] mb-2 mt-2" style="letter-spacing: -0.02em;">The support library is used by the framework to add support classes in your Flutter projects.</p>

				<div class="flex flex-wrap">
					<a href="https://github.com/{{ $resourceData['support']['organization'] }}/{{ $resourceData['support']['repository'] }}/releases/tag/{{ $resourceData['support']['version'] }}" class="inline-flex self-center text-[#6C7379]">
					Release Notes <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>

				<img src="{{ asset('images/rectangle.png') }}" class="w-0.5 mx-2 h-5 m-auto self-center">

				<a href="{{ $resourceData['support']['repository_url'] }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Repository <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
				</div>
		</div>


		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
			<img src="{{ asset('images/flutter_packages.png') }}" class="mb-6 h-[50px]" alt="Flutter packages">
				<span class="font-semibold text-[20px]">Flutter Packages</span>
				
				<p class="text-[18px] text-[#81888E] clear-both mb-2 mt-2" style="letter-spacing: -0.02em;">Discover all our public Flutter packages available on pub.dev.</p>

				<span class="inline-flex self-center">
					
					<a href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					View packages <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
				</span>
		</div>

	</div>


</section>

@endsection
