@extends('layouts.app-landing')

@section('content')

<section class="pt-[80px] px-7 md:px-0">
	<h1 class="text-5xl text-center sora mb-2">{{ config('app.name') }}'s <span class="text-primary-blue-deep sora font-semibold">Ecosystem</span></h1>

	<h2 class="text-center mb-10 text-2xl">Packages &amp; services to help you build with Flutter apps easier.</h2>
</section>

<section class="mx-auto">

	<div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-7 md:px-0 max-w-[806px] mx-auto">

		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
			<img src="{{ asset('images/phone.png') }}" class="mb-6 h-[50px]" alt="Create a {{ config('app.name') }} app">
				<span class="font-semibold text-[20px]">{{ config('app.name') }} Permission Policy</span>

				<span class="bg-[#ECF5FC] ml-2 px-2 text-primary-blue-deep rounded-3xl py-1 font-semibold">v1.1.6</span>

				<p class="text-[18px] text-[#81888E] mb-2 mt-2" style="letter-spacing: -0.02em;">Handle roles and permissions in your Flutter app.</p>

				<div class="flex flex-wrap">

					<a href="https://pub.dev/packages/permission_policy" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                        View <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>

				<img src="{{ asset('images/rectangle.png') }}" class="w-0.5 mx-2 h-5 m-auto self-center">

				<a href="https://github.com/nylo-core/permission_policy/releases" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Release Notes <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
				</div>
		</div>


		<div class="hover:bg-gray-50 p-[24px] rounded border border-2 border-gray-100">
			<img src="{{ asset('images/framework.png') }}" class="mb-6 h-[50px]" alt="{{ config('app.name') }} framework library">
				<span class="font-semibold text-[20px]">{{ config('app.name') }} Payslip</span>

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
