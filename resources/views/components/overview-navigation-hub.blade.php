@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">{{ __('Step 1') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Create a Navigation Hub') }}</p>

<x-code-highlighter language="bash" title="terminal" class="col-span-1 mb-5">
metro make:navigation_hub base
</x-code-highlighter>

<x-code-highlighter language="dart" title="resources/pages/base_navigation_hub.dart" class="col-span-1 mb-5">
class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();

    @override
    bool get maintainState => true;

    _BaseNavigationHubState() : super(() async {
        return {
            0: NavigationTab(
                title: "Home",
                page: HomeTab(),
                icon: Icon(Icons.home),
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
            ),
        };
    });
}
</x-code-highlighter>

<small class="font-[sora] text-gray-500">{{ __('Step 2') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Switch layouts easily') }}</p>

<x-code-highlighter language="dart" header="false" class="col-span-1">
// Bottom navigation
NavigationHubLayout.bottomNav()

// Top navigation
NavigationHubLayout.topNav()

// Journey / wizard flow
NavigationHubLayout.journey()
</x-code-highlighter>

                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">{{ __('Build bottom nav, top nav, or journey flows with state maintenance.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'navigation-hub']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            {{ __('Learn more') }} <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => __('Bottom Nav'),
                                    'link' => 'bottom-navigation'
                                ],
                                [
                                    'title' => __('Top Nav'),
                                    'link' => 'top-navigation'
                                ],
                                [
                                    'title' => __('Journey Flow'),
                                    'link' => 'journey-navigation'
                                ],
                                [
                                    'title' => __('Badges'),
                                    'link' => 'adding-badges-to-tabs'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'navigation-hub']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
