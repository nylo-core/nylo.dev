@extends('layouts.app-landing')

@section('content')

<section class="relative bg-center bg-repeat-x" style="background-image: url('{{ asset("/images/hero_fade.png") }}')">
	<div class="container lg:px-32 md:px-32 mx-auto px-14 pt-40 pb-20 xl:px-32">

		@if (!empty($event))
        <div class="text-center mb-5">
			@if($event->isHappeningNow())
            <div class="block text-xs text-gray-500 mb-2">
                <span class="">{{ $event->start_date->format('jS F, Y H:i') }}</span> <span class="border-l pl-1 border-gray-100">ICT</span>
            </div>
			<a href="{{ $event->link }}" target="_BLANK" class="text-gray-600"><b class="text-primary-blue"">{{ $event->title }}</b> - Our next online meet up <span class="bg-green-50 border-2 border-green-100 font-mono px-3 py-1 rounded rounded-2xl text-green-800 text-xs font-semibold">Happening now | Join</span></a>
			@elseif($event->isUpcoming())
            <div class="block text-xs text-gray-500 mb-2">
                <span class="">{{ $event->start_date->format('jS F, Y H:i') }}</span> <span class="border-l pl-1 border-gray-100">ICT</span>
            </div>
			<a href="{{ $event->link }}" target="_BLANK" class="text-gray-600"><b class="text-primary-blue"">{{ $event->title }}</b> - Our next online meet up <span class="bg-green-50 border-2 border-green-100 font-mono px-3 py-1 rounded text-gray-600 text-xs font-semibold">Join via Google Meet 🗓️</span></a>
			@endif
        </div>
		@endif

		<h1 class="font-medium lg:max-w-3xl lg:text-7xl m-auto mb-8 md:text-5xl mx-auto text-5xl text-center w-full">The Flutter <br><span class="text-h1-gradient font-semibold clear-both">Micro-framework</span><br> For Modern Apps</h1>
		<h2 class="mb-7 text-center text-lg text-primary-gray">A solid foundation for building Flutter apps.</h2>

		<div class="w-max mx-auto self-center flex mt-8">
			<a href="{{ route('landing.download') }}" class="bg-white inline-flex justify-center border mx-auto px-4 py-2 mr-[12px] rounded w-max hover:border-gray-600 transition-all shadow-sm">
				<i class="ri-github-fill mr-2 text-gray-400 hover:text-gray-500 transition-all"></i>
				<span>Download</span>
			</a>

			<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}" class="bg-primary-blue-deep block transition-all px-4 py-2 rounded text-white hover:bg-[#1E74C1]" target="_BLANK">Get Started</a>
		</div>
	</div>

</section>

<section class="relative max-w-screen-xl w-full mx-auto px-5">
	<div class="gap-20 grid grid-cols-1 md:grid-cols-2 mx-auto">

		<div class="self-center px-4 md:px-0">
			<h3 class="text-4xl mb-2 font-medium">Get Started!</h3>
			<h4 class="font-semibold mb-7 text-4xl text-primary-blue font-[sora]">Develop your next Flutter app with Nylo</h4>

			<div class="flex flex-wrap mb-5 text-primary-blue">
				@foreach(['Router', 'Storage', 'Networking', 'Themes and Styling', 'Configuration', 'Metro', 'Forms'] as $docSection)

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

			<p class="mb-8 text-[#484D50]"><span class="font-bold">{{ config('app.name') }}</span> is a powerful framework for developing mobile apps in Flutter. Out the box it comes with a router, secure storage, networking and more.</p>

			<a class="bg-white inline-flex justify-center border font-medium mx-auto px-6 py-3 rounded-lg w-max hover:border-gray-400 transition-all shadow-sm" href="{{ route('landing.download') }}">

				<i class="ri-github-fill mr-2"></i>

				<span>Download</span>
			</a>

		</div>

		<div>
			<img class="mx-auto" src="{{ asset('images/showcase.png') }}" alt="Showcase projects built using Nylo">
		</div>

	</div>
</section>

<section class="relative max-w-screen-xl w-full mx-auto px-5 pt-12 pb-16 md:pt-24 lg:pt-48">

		<h3 class="px-5 md:px-0 text-4xl mb-3 font-medium">Powerful tools for <span class="text-primary-blue font-semibold font-[sora]">creating</span></h3>
		<p class="pb-5 px-5 md:px-0 block border-b-4 border-gray-50 font-semibold mb-8 text-2xl text-gray-300">Everything you need to build your next Flutter app</p>

	<div class="grid grid-cols-1 md:grid-cols-4 gap-10">
		<div class="border-b pb-10 col-span-1 md:col-span-4 gap-10 justify-center inline-flex flex-col md:flex-auto mb-10 md:flex-row" x-data="{
            currentTab: 'routing',
        }">

                <div class="w-full lg:w-[500px]">
                    <ul class="text-sm lg:text-base font-medium flex flex-col gap-3 overflow-x-auto lg:overflow-x-visible lg:overflow-y-auto lg:overscroll-y-contain">
                        <li class="cursor-pointer hover:shadow py-2 text-left p-4 lg:py-6 flex items-center gap-3 transition-all bg-gray-50 text-gray-700 hover:bg-white" x-on:click="currentTab = 'routing'" :class="{ 'gradient-boarder-selected shadow bg-white': currentTab === 'routing' }">
                            <svg class="h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.96 4.13a.75.75 0 0 1 .369-1.264l4.767-1.045a.75.75 0 0 1 .893.893l-1.046 4.767a.75.75 0 0 1-1.262.37L6.959 4.129zm6.737 18.465a3.1 3.1 0 1 0 0-6.2 3.1 3.1 0 0 0 0 6.2zM7.407 7.403a1 1 0 0 0-1.414 0L3.69 9.705a4.246 4.246 0 0 0 0 6.005l.004.003a4.253 4.253 0 0 0 6.01-.003l6.005-6.005c.88-.88 2.305-.88 3.185-.002.878.876.879 2.298.003 3.176l-.002.001-1.77 1.77a1 1 0 0 0 1.414 1.415l1.77-1.77.004-.004a4.246 4.246 0 0 0-.007-6.004 4.253 4.253 0 0 0-6.01.003L8.29 14.295c-.879.88-2.304.88-3.185 0a2.246 2.246 0 0 1 0-3.175l2.302-2.303a1 1 0 0 0 0-1.414z" fill="#000000"></path></g></svg>
                            Routing
                        </li>
                        <li class="cursor-pointer hover:shadow py-2 text-left p-4 lg:py-6 flex items-center gap-3 transition-all bg-gray-50 text-gray-700 hover:bg-white" x-on:click="currentTab = 'authentication'" :class="{ 'gradient-boarder-selected shadow bg-white': currentTab === 'authentication' }">
                            <svg class="h-5" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="m 8.074219 0 c -1.203125 -0.0117188 -2.40625 0.285156 -3.492188 0.890625 c -0.480469 0.269531 -0.652343 0.878906 -0.382812 1.359375 c 0.269531 0.484375 0.878906 0.65625 1.359375 0.386719 c 1.550781 -0.867188 3.4375 -0.847657 4.972656 0.050781 c 1.53125 0.898438 2.46875 2.535156 2.46875 4.3125 v 1 c 0 0.550781 0.449219 1 1 1 s 1 -0.449219 1 -1 v -1 c 0 -0.019531 0 -0.039062 -0.003906 -0.054688 c -0.019532 -2.460937 -1.332032 -4.738281 -3.457032 -5.984374 c -1.070312 -0.628907 -2.265624 -0.9492192 -3.46875 -0.960938 z m -5.199219 2.832031 c -0.066406 0 -0.132812 0.007813 -0.195312 0.023438 c -0.257813 0.058593 -0.484376 0.21875 -0.625 0.445312 c -0.6875 1.109375 -1.054688 2.390625 -1.054688 3.699219 v 5.0625 c 0 0.550781 0.449219 1 1 1 s 1 -0.449219 1 -1 v -5.0625 c 0 -0.933594 0.261719 -1.851562 0.753906 -2.644531 c 0.292969 -0.46875 0.148438 -1.082031 -0.320312 -1.375 c -0.167969 -0.105469 -0.363282 -0.15625 -0.558594 -0.148438 z m 5.125 0.167969 c -2.199219 0 -4 1.800781 -4 4 v 1 c 0 0.550781 0.449219 1 1 1 s 1 -0.449219 1 -1 v -1 c 0 -1.117188 0.882812 -2 2 -2 s 2 0.882812 2 2 v 5 s 0.007812 0.441406 0.175781 0.941406 s 0.5 1.148438 1.117188 1.765625 c 0.390625 0.390625 1.023437 0.390625 1.414062 0 s 0.390625 -1.023437 0 -1.414062 c -0.382812 -0.382813 -0.550781 -0.734375 -0.632812 -0.984375 s -0.074219 -0.308594 -0.074219 -0.308594 v -5 c 0 -2.199219 -1.800781 -4 -4 -4 z m 0 3 c -0.550781 0 -1 0.449219 -1 1 v 5 s 0 0.59375 0.144531 1.320312 c 0.144531 0.726563 0.414063 1.652344 1.148438 2.386719 c 0.390625 0.390625 1.023437 0.390625 1.414062 0 s 0.390625 -1.023437 0 -1.414062 c -0.265625 -0.265625 -0.496093 -0.839844 -0.601562 -1.363281 c -0.105469 -0.523438 -0.105469 -0.929688 -0.105469 -0.929688 v -5 c 0 -0.550781 -0.449219 -1 -1 -1 z m -3 4 c -0.550781 0 -1 0.449219 -1 1 v 3 c 0 0.550781 0.449219 1 1 1 s 1 -0.449219 1 -1 v -3 c 0 -0.550781 -0.449219 -1 -1 -1 z m 9 0 c -0.550781 0 -1 0.449219 -1 1 s 0.449219 1 1 1 s 1 -0.449219 1 -1 s -0.449219 -1 -1 -1 z m 0 0" fill="#2e3434"></path> </g></svg>
                            Authentication
                        </li>
                        <li class="cursor-pointer hover:shadow py-2 text-left p-4 lg:py-6 flex items-center gap-3 transition-all bg-gray-50 text-gray-700 hover:bg-white" x-on:click="currentTab = 'forms'" :class="{ 'gradient-boarder-selected shadow bg-white': currentTab === 'forms' }">
                            <svg class="h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M5.5 8.557A2.08 2.08 0 0 1 7 8v1c-.74 0-.948.417-1 .571v5.86c.048.143.251.569 1 .569v1a2.08 2.08 0 0 1-1.5-.557A2.08 2.08 0 0 1 4 17v-1c.74 0 .948-.417 1-.571v-5.86C4.952 9.426 4.749 9 4 9V8a2.08 2.08 0 0 1 1.5.557zM23 6.5v12a1.502 1.502 0 0 1-1.5 1.5h-19A1.502 1.502 0 0 1 1 18.5v-12A1.502 1.502 0 0 1 2.5 5h19A1.502 1.502 0 0 1 23 6.5zm-1 0a.5.5 0 0 0-.5-.5h-19a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 .5.5h19a.5.5 0 0 0 .5-.5zM12 17h1v-1h-1zm-2 0h1v-1h-1zm-2 0h1v-1H8zm6 0h1v-1h-1zm4 0h1v-1h-1zm-2 0h1v-1h-1z"></path><path fill="none" d="M0 0h24v24H0z"></path></g></svg>
                            Forms
                        </li>
                        <li class="cursor-pointer hover:shadow py-2 text-left p-4 lg:py-6 flex items-center gap-3 transition-all bg-gray-50 text-gray-700 hover:bg-white" x-on:click="currentTab = 'state-management'" :class="{ 'gradient-boarder-selected shadow bg-white': currentTab === 'state-management' }">
                            <svg class="h-5" version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .stone_een{fill:#0B1719;} .st0{fill:#0B1719;} </style> <path class="stone_een" d="M19,20.5c0,1.933-1.567,3.5-3.5,3.5S12,22.433,12,20.5s1.567-3.5,3.5-3.5S19,18.567,19,20.5z M16,16.051 V5.5C16,5.224,15.776,5,15.5,5S15,5.224,15,5.5v10.551C15.166,16.032,15.329,16,15.5,16S15.834,16.032,16,16.051z M15,24.949V26.5 c0,0.276,0.224,0.5,0.5,0.5s0.5-0.224,0.5-0.5v-1.551C15.834,24.968,15.671,25,15.5,25S15.166,24.968,15,24.949z M24,18.949V26.5 c0,0.276,0.224,0.5,0.5,0.5s0.5-0.224,0.5-0.5v-7.551C24.834,18.968,24.671,19,24.5,19S24.166,18.968,24,18.949z M25,10.051V5.5 C25,5.224,24.776,5,24.5,5S24,5.224,24,5.5v4.551C24.166,10.032,24.329,10,24.5,10S24.834,10.032,25,10.051z M7,7.051V5.5 C7,5.224,6.776,5,6.5,5S6,5.224,6,5.5v1.551C6.166,7.032,6.329,7,6.5,7S6.834,7.032,7,7.051z M6,15.949V26.5 C6,26.776,6.224,27,6.5,27S7,26.776,7,26.5V15.949C6.834,15.968,6.671,16,6.5,16S6.166,15.968,6,15.949z M6.5,8 C4.567,8,3,9.567,3,11.5S4.567,15,6.5,15s3.5-1.567,3.5-3.5S8.433,8,6.5,8z M24.5,11c-1.933,0-3.5,1.567-3.5,3.5s1.567,3.5,3.5,3.5 s3.5-1.567,3.5-3.5S26.433,11,24.5,11z"></path> </g></svg>
                            State Management
                        </li>
                        <li class="cursor-pointer hover:shadow py-2 text-left p-4 lg:py-6 flex items-center gap-3 transition-all bg-gray-50 text-gray-700 hover:bg-white" x-on:click="currentTab = 'events'" :class="{ 'gradient-boarder-selected shadow bg-white': currentTab === 'events' }">
                            <svg class="h-5" fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M71.4,28.6C65.9,23.1,58.6,20,50.9,20c-1.7,0-3,1.3-3,3s1.3,3,3,3c6.1,0,11.9,2.4,16.3,6.7 C71.5,37.2,74,43,74,49.1c0,1.7,1.3,3,3,3s3-1.3,3-3C80,41.4,77,34.1,71.4,28.6z M50.9,32.1c-1.7,0-3,1.3-3,3s1.3,3,3,3 c2.9,0,5.7,1.1,7.8,3.2c2.1,2.1,3.2,4.8,3.2,7.8c0,1.7,1.3,3,3,3c1.7,0,3-1.3,3-3c0-4.5-1.8-8.8-5-12S55.4,32.1,50.9,32.1z M46.6,60.8l2.6-7c1.8,0.7,3.8,0.3,5.3-1.1c2-2,2-5.1,0-7.1c-2-2-5.1-2-7.1,0c-1.5,1.5-1.8,3.7-1,5.6l-6.5,2.9L28.2,42.4 c-0.8-0.8-2.2-0.8-2.9,0.1c-7.5,9-7,22.4,1.5,30.9c8.4,8.4,21.8,8.9,30.9,1.5c0.9-0.7,0.9-2.1,0.1-2.9 C57.7,71.9,46.6,60.8,46.6,60.8z"></path> </g> </g> </g></svg>
                            Events
                        </li>
                        <li class="cursor-pointer hover:shadow py-2 text-left p-4 lg:py-6 flex items-center gap-3 transition-all bg-gray-50 text-gray-700 hover:bg-white" x-on:click="currentTab = 'scheduler'" :class="{ 'gradient-boarder-selected shadow bg-white': currentTab === 'scheduler' }">
                            <svg class="h-5" fill="#000000" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M531 624l-88-1q18-44 51.5-76.5T572 500q55-18 110.5-3.5T778 554q4 5 10.5 5t11.5-4l26-24q5-5 5.5-12t-4.5-12q-53-58-127-77.5T553 433q-65 21-112.5 71.5T373 623h-93q-4 0-5 3t1 6l126 144q1 1 3 1t4-1l126-143q2-3 1-6t-5-3zm451 143L857 623q-2-2-4-2t-3 2L724 766q-3 2-1.5 5.5t4.5 3.5h88q-17 45-51 77.5T686 899q-55 18-110 3t-95-57q-5-5-11-5.5t-11 4.5l-26 23q-5 5-5.5 12t4.5 13q38 41 88.5 63.5T626 978q41 0 80-13 65-21 112.5-71T885 776h94q3 0 4.5-3.5T982 767zM70 252v447q0 14 9.5 23.5T103 732h127q6 0 11-4.5t5-11.5v-22q0-7-5-12t-11-5H125V296h568v56q0 7 4.5 12t11.5 5h21q7 0 12-5t5-12V252H70zm677-32v-55q0-13-9.5-23T714 132H613v-13q0-12-6-23t-16.5-17-22.5-6-22.5 6T529 96t-6 23v13H293v-13q0-19-13-32t-31.5-13T217 87t-13 32v13H103q-14 0-23.5 10T70 165v55h677z"></path></g></svg>
                            Scheduler
                        </li>
                    </ul>
                </div>

<div class="w-full">

    <div x-show="currentTab === 'routing'">
    <x-overview-router :latestVersionOfNylo="$latestVersionOfNylo"></x-overview-router>
    </div>

    <div x-show="currentTab === 'authentication'">
<x-overview-authentication :latestVersionOfNylo="$latestVersionOfNylo"></x-overview-authentication>
    </div>

    <div x-show="currentTab === 'forms'">
    <x-overview-forms :latestVersionOfNylo="$latestVersionOfNylo"></x-overview-forms>
    </div>

    <div x-show="currentTab === 'state-management'">
    <x-overview-state-management :latestVersionOfNylo="$latestVersionOfNylo"></x-overview-state-management>
    </div>

    <div x-show="currentTab === 'events'">
    <x-overview-events :latestVersionOfNylo="$latestVersionOfNylo"></x-overview-events>
    </div>

    <div x-show="currentTab === 'scheduler'">
    <x-overview-scheduler :latestVersionOfNylo="$latestVersionOfNylo"></x-overview-scheduler>
    </div>

</div>
		</div>

		<div class="md:col-span-2">

			<div>
				<div class="flex gap-4 mb-5">

					<div>
						<span class="font-semibold text-3xl text-[#484D50]">Make things from the terminal</span>
					<p class="mt-2 text-[18px] text-[#979DA2]" style="letter-spacing: -0.02em;">We have built a cli tool called Metro, you can create almost anything on the fly.</p>

                    <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'metro']) }}" class="inline-flex self-center text-[#6C7379]" target="_BLANK">
					Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				    </a>
					</div>
				</div>

<x-code-highlighter language="bash" header="false" title="routes/router.dart" class="max-w-4xl">
metro make:page HomePage
# Creates a new page called HomePage

metro make:api_service UserService
# Creates a new API Service called UserService

metro make:model User
# Creates a new model called User

metro make:stateful_widget FavouriteWidget
# Creates a new stateful widget called FavouriteWidget
</x-code-highlighter>

			</div>

			<div class="hidden md:block mt-5">
				<div class="flex gap-4">
					<div>
						<h5 class="font-semibold text-2xl">Learn {{ config('app.name') }}</h5>
						<p class="mt-2 text-[18px] text-[#979DA2]" style="letter-spacing: -0.02em;">Gain more knowledge about {{ config('app.name') }} through our extensive documentation.</p>
						<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}" class="mt-2 bg-primary-blue-deep inline-block transition-all px-4 py-2 rounded text-white hover:bg-[#1E74C1]" target="_BLANK">Documentation</a>
					</div>
				</div>
			</div>
		</div>

		<div class="md:col-span-2">

			<div class="mb-5">
				<h4 class="font-semibold text-2xl text-[#484D50]">Effortless API Networking</h4>
				<p class="mt-2 text-[18px] text-[#979DA2]" style="letter-spacing: -0.02em;">Networking class that makes writing API Services a breeze.</p>

				<a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'networking']) }}" class="inline-flex self-center text-[#6C7379]">
					Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
				</a>
			</div>

<x-code-highlighter language="dart" header="false" title="app/networking/api_service.dart" class="max-w-4xl">
class ApiService extends NyApiService {

    @override
    String get baseUrl => "api.example.com/v1";

    Future userInfo() async {
        return await network(
            request: (request) => request.get("/user"),
        );
    }
}

...

class _HomePageState extends NyState<HomePage> {

@override
init() async {
    User user = await api<ApiService>((request) => request.userInfo());
}
</x-code-highlighter>

		</div>

	</div>
</section>

<div style="background-image: url('{{ asset("/images/bg_hero.png") }}')" class="pt-40">

<section class="relative max-w-screen-xl w-full mx-auto px-5">

	<div class="backdrop-blur-[1px] bg-white/50 py-3" style="border-radius: 200px;">
		<div class="backdrop-blur-2xl">

			<h4 class="text-center text-4xl mb-2 font-medium">{{ config('app.name') }}'s <span class="font-semibold text-primary-blue">Community</span></h4>
			<p class="font-light mb-4 text-center text-gray-600 text-[20px]">Together, we can build better Flutter apps.</p>
	</div>

	<div class="text-center">
		<a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK" class="bg-white inline-flex justify-center border font-medium mx-auto px-6 py-3 rounded-lg w-max hover:border-gray-400 transition-all shadow-sm">Join the discussion

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

        <blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">"Nylo" is the best framework for flutter, which make it <span class="text-primary-blue">developing</span> easy such as, file-structure, routing, state, color theme and so on.</p>
			<span class="clear-both text-gray-400">@higakijin</span>
		</blockquote>

        <blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">This is <span class="text-primary-blue">incredible</span>. Very well done!</p>
			<span class="clear-both text-gray-400">FireflyDaniel</span>
		</blockquote>

        <blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">Big <span class="text-primary-blue">support</span> and love from morocco <3</p>
			<span class="clear-both text-gray-400">@imlian</span>
		</blockquote>

        <blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">Very nice Framework! <span class="text-primary-blue">Thank you so much!</span></p>
			<span class="clear-both text-gray-400">@ChaoChao2509</span>
		</blockquote>

        <blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">I just discover this framework and <span class="text-primary-blue">i'm verry impressed</span>. Thank you</p>
			<span class="clear-both text-gray-400">@lepresk</span>
		</blockquote>

        <blockquote class="border border-gray-200 rounded bg-white/50 backdrop-blur-[2.5px] px-5 py-5 flex justify-between flex-col">
			<p class="font-medium text-[#2F3234]">Great work on Nylo</p>
			<span class="clear-both text-gray-400">@dylandamsma</span>
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
