<div class="bg-[url('{{ asset('images/bg_hero.png') }}')] pt-[150px]" style="background-size: contain;">

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
                    <li><a class="text-gray-600 hover:text-gray-500 transition-all" target="_BLANK" href="https://github.com/nylo-core/nylo" target="_BLANK">Contributions</a></li>
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