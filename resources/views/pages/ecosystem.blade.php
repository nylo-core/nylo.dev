@extends('layouts.app-landing')

@section('content')

<section class="pt-[80px] px-7 md:px-0">
	<h1 class="text-5xl text-center sora mb-2">{{ config('app.name') }}'s <span class="text-primary-blue-deep sora font-semibold">Ecosystem</span></h1>

	<h2 class="text-center mb-10 text-2xl">Packages &amp; services to help you build with Flutter apps easier.</h2>
</section>

<section class="mx-auto">

	<div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-7 md:px-0 max-w-[806px] mx-auto">

		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
				<span class="font-semibold text-[20px]">{{ config('app.name') }} Permission Policy</span>

				<span class="bg-[#ECF5FC] ml-2 px-2 text-primary-blue-deep rounded-3xl py-1 font-semibold">{{ $resourceData['permission_policy']['version'] }}</span>

				<p class="text-[18px] text-[#81888E] mb-2 mt-2" style="letter-spacing: -0.02em;">Handle roles and permissions in your Flutter app.</p>

				<div class="flex flex-wrap">

					<a href="{{ $resourceData['permission_policy']['repository_url'] }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                        View <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>

				<img src="{{ asset('images/rectangle.png') }}" class="w-0.5 mx-2 h-5 m-auto self-center">

				<a href="{{ $resourceData['permission_policy']['release_note_url'] }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Release Notes <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
				</div>
		</div>

        <div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
				<span class="font-semibold text-[20px]">Media Pro</span>

				<span class="bg-[#ECF5FC] ml-2 px-2 text-primary-blue-deep rounded-3xl py-1 font-semibold">{{ $resourceData['media_pro']['version'] }}</span>

				<p class="text-[18px] text-[#81888E] mb-2 mt-2" style="letter-spacing: -0.02em;">Package for handling Media in your Flutter project.</p>

				<div class="flex flex-wrap">
					<a href="{{ $resourceData['media_pro']['repository_url'] }}" class="inline-flex self-center text-[#6C7379]">
					View <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>

				<img src="{{ asset('images/rectangle.png') }}" class="w-0.5 mx-2 h-5 m-auto self-center">

                <a href="{{ $resourceData['media_pro']['release_note_url'] }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Release Notes <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
				</div>
		</div>


		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
				<span class="font-semibold text-[20px]">{{ config('app.name') }} - IAP Paywall</span>

				<p class="text-[18px] text-[#81888E] mb-2 mt-2" style="letter-spacing: -0.02em;">Handle In-app Purchases for your Flutter project.</p>

				<div class="flex flex-wrap">
					<a href="#" class="inline-flex self-center text-[#6C7379]">
					Coming soon <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
			</div>
		</div>

	</div>
</section>

@endsection
