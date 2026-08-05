@props(['latestVersionOfNylo'])

<x-code-highlighter language="dart" title="routes/router.dart">
appRouter() => nyRoutes((router) {
    router.add(HomePage.path).initialRoute();

    router.add(DiscoverPage.path);

    router.add(LoginPage.path, 
        transitionType: TransitionType.bottomToTop());

    router.add(ProfilePage.path,
        routeGuard: [
            AuthGuard()
        ]
    );
});
</x-code-highlighter>
    <p class="ny-tab-blurb">{{ __('Build complex routes, interfaces and UI pages for your Flutter application.') }}</p>
    <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'router']) }}" target="_BLANK" class="ny-tab-link">
        {{ __('Learn more') }} <span aria-hidden="true">↗</span>
    </a>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-5">
            @foreach([
                [
                    'title' => __('Adding Routes'),
                    'link' => 'adding-routes'
                ],
                [
                    'title' => __('Navigating to pages'),
                    'link' => 'navigating-to-pages'
                ],
                [
                    'title' => __('Route Guards'),
                    'link' => 'route-guards'
                ],
                [
                    'title' => __('Deep linking'),
                    'link' => 'deep-linking'
                ],
                ] as $item)
                <a class="ny-chip justify-center py-2.5" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'router']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
            @endforeach
    </div>
