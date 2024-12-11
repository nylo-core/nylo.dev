@props(['latestVersionOfNylo'])

<p class="mb-1">Create a Navigation Hub widget</p>

<x-code-highlighter language="bash" title="terminal" class="col-span-1 mb-5">
metro make:navigation_hub base
</x-code-highlighter>

<x-code-highlighter language="dart" title="resources/pages/base_navigation_hub.dart" class="col-span-1 mb-5">
...
class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

    /// Layouts:
    /// - [NavigationHubLayout.bottomNav] Bottom navigation
    /// - [NavigationHubLayout.topNav] Top navigation
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
    // backgroundColor: Colors.white,
    );

    /// Should the state be maintained
    @override
    bool get maintainState => true;

    /// Navigation pages
    _BaseNavigationHubState() : super(() async {
    return {
        0: NavigationTab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    };
    });
}
</x-code-highlighter>

Change the layout to top navigation layout with `NavigationHubLayout.topNav`

<x-code-highlighter language="dart" title="resources/pages/base_navigation_hub.dart" class="col-span-1 mb-5">
class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        ...
    );
}
</x-code-highlighter>
