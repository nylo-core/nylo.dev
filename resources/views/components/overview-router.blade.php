@props(['latestVersionOfNylo'])

<x-code-highlighter language="dart" title="routes/router.dart">
appRouter() => nyRoutes((router) {
    router.route(HomePage.path, (context) => HomePage(), initialRoute: true);

    router.route(DiscoverPage.path, (context) => DiscoverPage());

    router.route(LoginPage.path, (context) => LoginPage());

    router.route(ProfilePage.path, (context) => ProfilePage(),
        routeGuard: [
            AuthGuard()
        ]
    );
});
</x-code-highlighter>
    <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">Build complex routes, interfaces and UI pages for your Flutter application.</p>
    <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'router']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
        Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
    </a>

        <div class="grid grid-cols-2 gap-5 mt-5">
            @foreach([
                [
                    'title' => 'Adding Routes',
                    'link' => 'adding-routes'
                ],
                [
                    'title' => 'Navigating to pages',
                    'link' => 'navigating-to-pages'
                ],
                [
                    'title' => 'Route Guards',
                    'link' => 'route-guards'
                ],
                [
                    'title' => 'Deep linking',
                    'link' => 'deep-linking'
                ],
                ] as $item)
                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'router']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
            @endforeach
    </div>
