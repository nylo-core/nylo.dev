@extends('layouts.app-landing')

@section('content')

<section class="relative bg-[url('{{ asset('images/hero_fade.png') }}')] bg-cover bg-bottom">
	<div class="container lg:px-32 md:px-32 mx-auto px-14 py-48 xl:px-32">

		<h1 class="lg:max-w-3xl mx-auto font-medium inline lg:block lg:text-5xl mb-7 md:block md:text-4xl sm:inline text-5xl text-center">The Powerful <span class="text-primary-blue font-semibold">Micro-framework</span> for Flutter</h1>
		<h2 class="mb-7 text-center text-lg text-primary-gray">Developer tools to build Flutter apps elegantly.</h2>
		<div class="w-max mx-auto self-center flex">
			<a href="{{ route('landing.download') }}" class="bg-white inline-flex justify-center block border mx-auto px-4 py-2 mr-[12px] rounded w-max hover:border-gray-600 transition-all shadow-sm inline-block" href="#">

				<i class="ri-github-fill mr-2 text-gray-400 hover:text-gray-500 transition-all"></i>

				<span>Download</span>
			</a>

			<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}" class="bg-primary-blue-deep block transition-all px-4 py-2 inline-block rounded text-white hover:bg-[#1E74C1]" target="_BLANK">Get Started</a>
		</div>
	</div>

</section>

<section class="container mx-auto md:pt-[80px] md:pb-[114px] pt-20 mb-44">
	<div class="gap-20 grid grid-cols-1 md:grid-cols-2 mx-auto">
		
		<div class="self-center px-4 md:px-0">
			<h3 class="text-4xl mb-2 font-medium">Get Started!</h3>
			<h4 class="font-semibold mb-7 text-2xl text-4xl text-primary-blue sora">Deploy your next Flutter app with Nylo</h4>

			<div class="flex flex-wrap mb-5 text-primary-blue">
				@foreach(['Router', 'Storage', 'Networking', 'Themes and Styling', 'Configuration', 'Metro'] as $docSection)
				
				<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => strtolower(\Str::slug($docSection))]) }}" target="_BLANK">
				<div class="border px-3 py-1 rounded-2xl mr-3 mb-2">
					<span class="">{{ $docSection }}</span>

						<span class="ml-2 inline-flex justify-center">
							<svg width="10" height="10" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.9199 0.62C12.8185 0.375651 12.6243 0.181475 12.3799 0.0799999C12.2597 0.028759 12.1306 0.00157999 11.9999 0H1.99994C1.73472 0 1.48037 0.105357 1.29283 0.292893C1.1053 0.48043 0.999939 0.734784 0.999939 1C0.999939 1.26522 1.1053 1.51957 1.29283 1.70711C1.48037 1.89464 1.73472 2 1.99994 2H9.58994L1.28994 10.29C1.19621 10.383 1.12182 10.4936 1.07105 10.6154C1.02028 10.7373 0.994141 10.868 0.994141 11C0.994141 11.132 1.02028 11.2627 1.07105 11.3846C1.12182 11.5064 1.19621 11.617 1.28994 11.71C1.3829 11.8037 1.4935 11.8781 1.61536 11.9289C1.73722 11.9797 1.86793 12.0058 1.99994 12.0058C2.13195 12.0058 2.26266 11.9797 2.38452 11.9289C2.50638 11.8781 2.61698 11.8037 2.70994 11.71L10.9999 3.41V11C10.9999 11.2652 11.1053 11.5196 11.2928 11.7071C11.4804 11.8946 11.7347 12 11.9999 12C12.2652 12 12.5195 11.8946 12.707 11.7071C12.8946 11.5196 12.9999 11.2652 12.9999 11V1C12.9984 0.869323 12.9712 0.740222 12.9199 0.62Z" fill="#328DDF"/>
</svg>

						</span>
				</div>
			</a>
				@endforeach
			</div>

			<p class="mb-8 text-[#484D50]"><span class="font-bold">{{ config('app.name') }}</span> is a powerful platform for developing mobile apps in Flutter. Whether you're building or deploying, coding is a breeze.</p>

			<a class="bg-white inline-flex justify-center block border border-2 font-medium mx-auto px-6 py-3 rounded-lg w-max hover:border-gray-400 transition-all shadow-sm" href="{{ route('landing.download') }}">

				<i class="ri-github-fill mr-2"></i>

				<span>Download</span>
			</a>

		</div>

		<div>
			<img class="mx-auto" src="{{ asset('images/showcase.png') }}" alt="Showcase projects built using Nylo">
		</div>

	</div>
</section>

<section class="mx-auto px-4 container">
		
		<h3 class="px-5 md:px-0 text-4xl mb-3 font-medium">Powerful tools for <span class="text-primary-blue font-semibold sora">creating</span></h3>
		<p class="px-5 md:px-0 block border-b border-b-4 border-gray-50 font-semibold mb-8 text-2xl text-gray-300">Write code that works.</p>
	
	<div class="grid grid-cols-1 md:grid-cols-4">
		<div class="flex flex-col grid grid-cols-4 md:col-span-4 md:flex-row">
			<div class="col-span-3 md:hidden px-6">
				<h4 class="font-semibold text-5xl">Routing</h4>
				<p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">Build complex routes, interfaces and UI pages for your Flutter application.</p>
			</div>
			<img src="{{ asset('images/code_snippet.png') }}" alt="{{ config('app.name') }} router" class="col-span-4 md:col-span-3 2xl:col-span-2">

			<div class="self-center hidden md:block col-span-1 2xl:col-span-2">
				<h4 class="font-semibold text-5xl">Routing</h4>
				<p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">Build complex routes, interfaces and UI pages for your Flutter application.</p>
				<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'router']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
					Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
			</div>
		</div>

		<div class="md:col-span-2">

			<div>
				<div class="flex gap-4">
					
					<div class="px-6">
						<span class="font-semibold text-2xl">Secure Storage</span>
					<p class="mt-2 text-[18px] text-[#979DA2]" style="letter-spacing: -0.02em;">Secure storage out of the box! Class for quick access to data on the fly.</p>
					<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'storage']) }}" class="inline-flex self-center text-[#6C7379]" target="_BLANK">
					Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
					</div>
				</div>
				<img src="{{ asset('images/storage_snippet.png') }}" alt="Storing objects in {{ config('app.name') }}">
			</div>

			<div class="px-6 hidden md:block">
				<div class="flex gap-4">
					<div>
						<h5 class="font-semibold text-2xl">Learn {{ config('app.name') }}</h5>
						<p class="mt-2 text-[18px] text-[#979DA2]" style="letter-spacing: -0.02em;">Gain more knowledge about {{ config('app.name') }} through our extensive documentation.</p>
						<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}" class="mt-2 bg-primary-blue-deep block transition-all px-4 py-2 inline-block rounded text-white hover:bg-[#1E74C1]" target="_BLANK">Documentation</a>
					</div>
				</div>
			</div>
		</div>

		<div class="md:col-span-2">
			
			<div class="px-6">
				<h4 class="font-semibold text-2xl">Effortless API Networking</h4>
				<p class="mt-2 text-[18px] text-[#979DA2]" style="letter-spacing: -0.02em;">Networking class that makes writing API Services a breeze.</p>

				<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'networking']) }}" class="inline-flex self-center text-[#6C7379]">
					Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
			</div>
			<img src="{{ asset('images/api_service_code.png') }}" alt="Manage your API Services in {{ config('app.name') }}">
		</div>
	
	</div>
</section>

<div class="bg-[url('{{ asset('images/bg_hero.png') }}')]">

<section class="container mx-auto px-5 md:px-20 pt-[127px] pb-[113px] mt-[80px]">

	<div class="backdrop-blur-[1px] bg-white/50 py-3" style="border-radius: 200px;">
		<div class="backdrop-blur-2xl">
			
			<h4 class="text-center text-4xl mb-2 font-medium">{{ config('app.name') }}'s <span class="font-semibold text-primary-blue">Community</span></h4>
			<p class="font-light mb-4 text-center text-gray-600 text-[20px]">Together, we can build better Flutter apps.</p>
	</div>

	<div class="text-center">
		<a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK" class="bg-white inline-flex justify-center block border border-2 font-medium mx-auto px-6 py-3 rounded-lg w-max hover:border-gray-400 transition-all shadow-sm">Join the discussion 
		
		<span class="ml-3 self-center">
			<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.9199 0.62C12.8185 0.375651 12.6243 0.181475 12.3799 0.0799999C12.2597 0.028759 12.1306 0.00157999 11.9999 0H1.99994C1.73472 0 1.48037 0.105357 1.29283 0.292893C1.1053 0.48043 0.999939 0.734784 0.999939 1C0.999939 1.26522 1.1053 1.51957 1.29283 1.70711C1.48037 1.89464 1.73472 2 1.99994 2H9.58994L1.28994 10.29C1.19621 10.383 1.12182 10.4936 1.07105 10.6154C1.02028 10.7373 0.994141 10.868 0.994141 11C0.994141 11.132 1.02028 11.2627 1.07105 11.3846C1.12182 11.5064 1.19621 11.617 1.28994 11.71C1.3829 11.8037 1.4935 11.8781 1.61536 11.9289C1.73722 11.9797 1.86793 12.0058 1.99994 12.0058C2.13195 12.0058 2.26266 11.9797 2.38452 11.9289C2.50638 11.8781 2.61698 11.8037 2.70994 11.71L10.9999 3.41V11C10.9999 11.2652 11.1053 11.5196 11.2928 11.7071C11.4804 11.8946 11.7347 12 11.9999 12C12.2652 12 12.5195 11.8946 12.707 11.7071C12.8946 11.5196 12.9999 11.2652 12.9999 11V1C12.9984 0.869323 12.9712 0.740222 12.9199 0.62Z" fill="#9ca3af"/>
</svg>
		</span>

	</a>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-16">
		
		<blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">“I’m new to Dart and new to your framework <span class="text-primary-blue">(which I love)</span>”</p>
			<div>
				<span class="clear-both text-gray-400">Peter</span><br>
			<span class="text-gray-400">Senior Director of <span class="text-primary-blue font-medium">Heroku Global</span></span>
			</div>
		</blockquote>

		<blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">“I wanted to thank you guys for the great job you are doing.”</p>
			<span class="clear-both text-gray-400">@youssefKadaouiAbbassi</span>
		</blockquote>

		<blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">“Just to say that I am in love with <span class="text-primary-blue">@nylo_dev</span>'s website!! I mean, look at this: <span class="text-primary-blue">https://nylo.dev</span> Definitely gonna explore it!”</p>
			<span class="clear-both text-gray-400">@esfoliante_txt</span>
		</blockquote>

		<blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">“Really <span class="text-primary-blue">love the concept</span> of this framework”</p>
			<span class="clear-both text-gray-400 mt-5">@Chrisvidal</span>
		</blockquote>

		<blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">“Just discovered <span class="text-primary-blue">http://nylo.dev</span> and looking forward using in production.”</p>
			<span class="clear-both text-gray-400 mt-5">@medilox</span>
		</blockquote>

		<blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">“Nice Framework”</p>
			<span class="clear-both text-gray-400">@ChadereNyore</span>
		</blockquote>

	</div>
	</div>
</section>


<footer>
	<div>
		<div class="md:py-12 md:px-32 backdrop-blur-[3px] py-5 border-t">

		<div class="grid grid-cols-2 md:grid-cols-3 grid-flow-row gap-10 px-3 md:p-0 md:gap-20 container mx-auto">
			<div class="my-auto col-span-2 md:col-span-1">
				<img src="{{ asset('images/nylo_logo_filled.png') }}" alt="{{ config('app.name') }} logo" class="h-10 mb-3">
				<p class="mb-3 text-gray-600">{{ config('app.name') }} is a micro-framework for Flutter which is designed to help simplify developing apps. #nylo #flutter</p>
			</div>
			
			<div class="my-auto col-span-1 sm:col-span-1">
				<h5 class="mb-3 text-gray-400 text-sm">Documentation</h5>
				<ul class="list-unstyled gap-2 flex flex-col">
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'installation']) }}">Installation</a></li>
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'requirements']) }}">Requirements</a></li>
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'router']) }}">Router</a></li>
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'themes-and-styling']) }}" target="_BLANK">Themes & Styling</a></li>
                </ul>
			</div>
			
			<div class="my-auto col-span-1 sm:col-span-1">
				<h5 class="mb-3 text-gray-400 text-sm">Resources</h5>

				<ul class="list-unstyled gap-2 flex flex-col">
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK">Flutter Packages</a></li>
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="https://github.com/nylo-core/nylo">Contributions</a></li>
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="{{ route('landing.privacy-policy') }}">Privacy Policy</a></li>
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" href="{{ route('landing.terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                </ul>
			</div>
		</div>
	</div>

		<div class="bg-gray-50/90 md:px-32 px-4">
		<div class="grid grid-cols-2 container mx-auto py-7">
			<div class="my-auto">
				<span class="text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved</span>
			</div>

			<div class="my-auto flex flex-col justify-end">
				<div class="flex gap-4 justify-end">
                    <div class="list-inline-item">
                    	<a href="https://twitter.com/nylo_dev" target="_BLANK">
                    		<i class="ri-twitter-fill hover:text-gray-500 transition-all text-2xl text-gray-400"></i>
                    	</a>
                    </div>

                    <div class="list-inline-item">
                    	<a href="https://github.com/nylo-core/nylo" target="_BLANK">
                    		<i class="ri-github-fill hover:text-gray-500 transition-all text-2xl text-gray-400"></i>
                    	</a>
                    </div>

                    <div class="list-inline-item">
                    	<a href="https://www.youtube.com/@nylo_dev" target="_BLANK">
                    		<i class="ri-youtube-fill hover:text-gray-500 transition-all text-2xl text-gray-400"></i>
                    	</a>
                    </div>
                </div>
			</div>
		</div>
		</div>
	</div>
</footer>
</div>
@endsection