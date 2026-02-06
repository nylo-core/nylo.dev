# Router

---

<a name="section-1"></a>

- [Introduction](#introduction "Introduction")
- Basics
  - [Adding routes](#adding-routes "Adding routes")
  - [Navigating to pages](#navigating-to-pages "Navigating to pages")
  - [Initial route](#initial-route "Initial route")
  - [Preview Route](#preview-route "Preview Route")
  - [Authenticated route](#authenticated-route "Authenticated route")
  - [Unknown Route](#unknown-route "Unknown Route")
- Sending data to another page
  - [Passing data to another page](#passing-data-to-another-page "Passing data to another page")
- Navigation
  - [Navigation types](#navigation-types "Navigation types")
  - [Navigating back](#navigating-back "Navigating back")
  - [Conditional Navigation](#conditional-navigation "Conditional Navigation")
  - [Page transitions](#page-transitions "Page transitions")
  - [Route History](#route-history "Route History")
  - [Update Route Stack](#update-route-stack "Update Route Stack")
- Route parameters
  - [Using Route Parameters](#route-parameters "Route Parameters")
  - [Query Parameters](#query-parameters "Query Parameters")
- Route Guards
  - [Creating Route Guards](#route-guards "Route Guards")
  - [NyRouteGuard Lifecycle](#nyroute-guard-lifecycle "NyRouteGuard Lifecycle")
  - [Guard Helper Methods](#guard-helper-methods "Guard Helper Methods")
  - [Parameterized Guards](#parameterized-guards "Parameterized Guards")
  - [Guard Stacks](#guard-stacks "Guard Stacks")
  - [Conditional Guards](#conditional-guards "Conditional Guards")
- Route Groups
  - [Route Groups](#route-groups "Route Groups")
- [Deep linking](#deep-linking "Deep linking")
- [Advanced](#advanced "Advanced")



<div id="introduction"></div>

## Introduction

Routes allow you to define the different pages in your app and navigate between them.

Use routes when you need to:
- Define the pages available in your app
- Navigate users between screens
- Protect pages behind authentication
- Pass data from one page to another
- Handle deep links from URLs

You can add routes inside the `lib/routes/router.dart` file.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **Tip:** You can create your routes manually or use the <a href="/docs/{{ $version }}/metro">Metro</a> CLI tool to create them for you.

Here's an example of creating an 'account' page using Metro.

``` bash
metro make:page account_page
```

``` dart
// Adds your new route automatically to /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

You may also need to pass data from one view to another. In {{ config('app.name') }}, that's possible using the `NyStatefulWidget` (a stateful widget with built-in route data access). We'll dive deeper into this to explain how it works.


<div id="adding-routes"></div>

## Adding routes

This is the easiest way to add new routes to your project.

Run the below command to create a new page.

```bash
metro make:page profile_page
```

After running the above, it will create a new Widget named `ProfilePage` and add it to your `resources/pages/` directory.
It will also add the new route to your `lib/routes/router.dart` file.

File: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Navigating to pages

You can navigate to new pages using the `routeTo` helper.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Initial route

In your routers, you can define the first page that should load by using the `.initialRoute()` method.

Once you've set the initial route, it will be the first page that loads when you open the app.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### Conditional Initial Route

You can also set a conditional initial route using the `when` parameter:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### Navigate to Initial Route

Use `routeToInitial()` to navigate to the app's initial route:

``` dart
void _goHome() {
    routeToInitial();
}
```

This will navigate to the route marked with `.initialRoute()` and clear the navigation stack.

<div id="preview-route"></div>

## Preview Route

During development, you may want to quickly preview a specific page without changing your initial route permanently. Use `.previewRoute()` to temporarily make any route the initial route:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

The `previewRoute()` method:
- Overrides any existing `initialRoute()` and `authenticatedRoute()` settings
- Makes the specified route the initial route
- Useful for quickly testing specific pages during development

> **Warning:** Remember to remove `.previewRoute()` before releasing your app!

<div id="authenticated-route"></div>

## Authenticated Route

In your app, you can define a route to be the initial route when a user is authenticated.
This will automatically override the default initial route and be the first page the user sees when they log in.

First, your user should be logged using the `Auth.authenticate({...})` helper.

Now, when they open the app the route you've defined will be the default page until they log out.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### Conditional Authenticated Route

You can also set a conditional authenticated route:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Navigate to Authenticated Route

You can navigate to the authenticated page using the `routeToAuthenticatedRoute()` helper:

``` dart
routeToAuthenticatedRoute();
```

**See also:** [Authentication](/docs/{{ $version }}/authentication) for details on authenticating users and managing sessions.


<div id="unknown-route"></div>

## Unknown Route

You can define a route to handle 404/not found scenarios using `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

When a user navigates to a route that doesn't exist, they'll be shown the unknown route page.


<div id="route-guards"></div>

## Route guards

Route guards protect pages from unauthorized access. They run before navigation completes, letting you redirect users or block access based on conditions.

Use route guards when you need to:
- Protect pages from unauthenticated users
- Check permissions before allowing access
- Redirect users based on conditions (e.g., onboarding not complete)
- Log or track page views

To create a new Route Guard, run the below command.

``` bash
metro make:route_guard dashboard
```

Next, add the new Route Guard to your route.

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Add your guard
    ]
  ); // restricted page
});
```

You can also set route guards using the `addRouteGuard` method:

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // or add multiple guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## NyRouteGuard Lifecycle

In v7, route guards use the `NyRouteGuard` class with three lifecycle methods:

- **`onBefore(RouteContext context)`** - Called before navigation. Return `next()` to continue, `redirect()` to go elsewhere, or `abort()` to stop.
- **`onAfter(RouteContext context)`** - Called after successful navigation to the route.

### Basic Example

File: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Perform a check if they can access the page
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Track page view after successful navigation
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

The `RouteContext` class provides access to navigation information:

| Property | Type | Description |
|----------|------|-------------|
| `context` | `BuildContext?` | Current build context |
| `data` | `dynamic` | Data passed to the route |
| `queryParameters` | `Map<String, String>` | URL query parameters |
| `routeName` | `String` | Route name/path |
| `originalRouteName` | `String?` | Original route name before transforms |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## Guard Helper Methods

### next()

Continue to the next guard or to the route:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

Redirect to a different route:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

The `redirect()` method accepts:

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | `Object` | Route path or RouteView |
| `data` | `dynamic` | Data to pass to the route |
| `queryParameters` | `Map<String, dynamic>?` | Query parameters |
| `navigationType` | `NavigationType` | Navigation type (default: pushReplace) |
| `transitionType` | `TransitionType?` | Page transition |
| `onPop` | `Function(dynamic)?` | Callback when route pops |

### abort()

Stop navigation without redirecting:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // User stays on current route
  }
  return next();
}
```

### setData()

Modify data passed to subsequent guards and the route:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Parameterized Guards

Use `ParameterizedGuard` when you need to configure guard behavior per-route:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Usage:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Guard Stacks

Compose multiple guards into a single reusable guard using `GuardStack`:

``` dart
// Create reusable guard combinations
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Conditional Guards

Apply guards conditionally based on a predicate:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Passing data to another page

In this section, we'll show how you can pass data from one widget to another.

From your Widget, use the `routeTo` helper and pass the `data` you want to send to the new page.

``` dart
// HomePage Widget
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage Widget (other page)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // or
    print(data()); // Hello World
  };
```

More examples

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Profile page widget (other page)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Route Groups

Route groups organize related routes and apply shared settings. They're useful when multiple routes need the same guards, URL prefix, or transition style.

Use route groups when you need to:
- Apply the same route guard to multiple pages
- Add a URL prefix to a set of routes (e.g., `/admin/...`)
- Set the same page transition for related routes

You can define a route group like in the below example.

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### Optional settings for route groups are:

| Setting | Type | Description |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Apply route guards to all routes in the group |
| `prefix` | `String` | Add a prefix to all route paths in the group |
| `transition_type` | `TransitionType` | Set transition for all routes in the group |
| `transition` | `PageTransitionType` | Set page transition type (deprecated, use transition_type) |
| `transition_settings` | `PageTransitionSettings` | Set transition settings |


<div id="route-parameters"></div>

## Using Route Parameters

When you create a new page, you can update the route to accept parameters.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Now, when you navigate to the page, you can pass the `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

You can access the parameters in the new page like this.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Query Parameters

When navigating to a new page, you can also provide query parameters.

Let's take a look.

```dart
  // Home page
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // navigate to profile page

  ...

  // Profile Page
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // or
    print(queryParameters()); // {"user": 7}
  };
```

> **Note:** As long as your page widget extends the `NyStatefulWidget` and `NyPage` class, then you can call `widget.queryParameters()` to fetch all the query parameters from the route name.

```dart
// Example page
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Home page
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // or
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Tip:** Query parameters must follow the HTTP protocol, E.g. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Page Transitions

You can add transitions when you navigate from one page by modifying your `router.dart` file.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### Available Page Transitions

#### Basic Transitions
- **`TransitionType.fade()`** - Fades the new page in while fading the old page out
- **`TransitionType.theme()`** - Uses the app theme's page transitions theme

#### Directional Slide Transitions
- **`TransitionType.rightToLeft()`** - Slides from right edge of screen
- **`TransitionType.leftToRight()`** - Slides from left edge of screen
- **`TransitionType.topToBottom()`** - Slides from top edge of screen
- **`TransitionType.bottomToTop()`** - Slides from bottom edge of screen

#### Slide with Fade Transitions
- **`TransitionType.rightToLeftWithFade()`** - Slides and fades from right edge
- **`TransitionType.leftToRightWithFade()`** - Slides and fades from left edge

#### Transform Transitions
- **`TransitionType.scale(alignment: ...)`** - Scales from specified alignment point
- **`TransitionType.rotate(alignment: ...)`** - Rotates around specified alignment point
- **`TransitionType.size(alignment: ...)`** - Grows from specified alignment point

#### Joined Transitions (Requires current widget)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Current page exits right while new page enters from left
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Current page exits left while new page enters from right
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Current page exits down while new page enters from top
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Current page exits up while new page enters from bottom

#### Pop Transitions (Requires current widget)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Current page exits to right, new page stays in place
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Current page exits to left, new page stays in place
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Current page exits down, new page stays in place
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Current page exits up, new page stays in place

#### Material Design Shared Axis Transitions
- **`TransitionType.sharedAxisHorizontal()`** - Horizontal slide and fade transition
- **`TransitionType.sharedAxisVertical()`** - Vertical slide and fade transition
- **`TransitionType.sharedAxisScale()`** - Scale and fade transition

#### Customization Parameters
Each transition accepts the following optional parameters:

| Parameter | Description | Default |
|-----------|-------------|---------|
| `curve` | Animation curve | Platform-specific curves |
| `duration` | Animation duration | Platform-specific durations |
| `reverseDuration` | Reverse animation duration | Same as duration |
| `fullscreenDialog` | Whether the route is a fullscreen dialog | `false` |
| `opaque` | Whether the route is opaque | `false` |


``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## Navigation Types

When navigating, you can specify one of the following if you are using the `routeTo` helper.

| Type | Description |
|------|-------------|
| `NavigationType.push` | Push a new page to your app's route stack |
| `NavigationType.pushReplace` | Replace the current route, disposing of the previous route once the new route finishes |
| `NavigationType.popAndPushNamed` | Pop the current route off the navigator and push a named route in its place |
| `NavigationType.pushAndRemoveUntil` | Push and remove routes until the predicate returns true |
| `NavigationType.pushAndForgetAll` | Push to a new page and dispose of any other pages on the route stack |

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## Navigating back

Once you're on the new page, you can use the `pop()` helper to go back to the existing page.

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // or
    Navigator.pop(context);
  }
...
```

If you want to return a value to the previous widget, provide a `result` like in the below example.

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Get the value from the previous widget using the `onPop` parameter
// HomePage Widget
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## Conditional Navigation

Use `routeIf()` to navigate only when a condition is met:

``` dart
// Only navigate if the user is logged in
routeIf(isLoggedIn, DashboardPage.path);

// With additional options
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

If the condition is `false`, no navigation occurs.


<div id="route-history"></div>

## Route History

In {{ config('app.name') }}, you can access the route history information using the below helpers.

``` dart
// Get route history
Nylo.getRouteHistory(); // List<dynamic>

// Get the current route
Nylo.getCurrentRoute(); // Route<dynamic>?

// Get the previous route
Nylo.getPreviousRoute(); // Route<dynamic>?

// Get the current route name
Nylo.getCurrentRouteName(); // String?

// Get the previous route name
Nylo.getPreviousRouteName(); // String?

// Get the current route arguments
Nylo.getCurrentRouteArguments(); // dynamic

// Get the previous route arguments
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Update Route Stack

You can update the navigation stack programmatically using `NyNavigator.updateStack()`:

``` dart
// Update the stack with a list of routes
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Pass data to specific routes
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | required | List of route paths to navigate to |
| `replace` | `bool` | `true` | Whether to replace the current stack |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Data to pass to specific routes |

This is useful for:
- Deep linking scenarios
- Restoring navigation state
- Building complex navigation flows


<div id="deep-linking"></div>

## Deep Linking

Deep linking allows users to navigate directly to specific content within your app using URLs. This is useful for:
- Sharing direct links to specific app content
- Marketing campaigns that target specific in-app features
- Handling notifications that should open specific app screens
- Seamless web-to-app transitions

## Setup

Before implementing deep linking in your app, ensure your project is properly configured:

### 1. Platform Configuration

**iOS**: Configure universal links in your Xcode project
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter Universal Links Configuration Guide</a>

**Android**: Set up app links in your AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter App Links Configuration Guide</a>

### 2. Define Your Routes

All routes that should be accessible via deep links must be registered in your router configuration:

```dart
// File: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Basic routes
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Route with parameters
  router.add(HotelBookingPage.path);
});
```

## Using Deep Links

Once configured, your app can handle incoming URLs in various formats:

### Basic Deep Links

Simple navigation to specific pages:

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

To trigger these navigations programmatically within your app:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Path Parameters

For routes that require dynamic data as part of the path:

#### Route Definition

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Define a route with a parameter placeholder {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Access the path parameter
    final hotelId = queryParameters()["id"]; // Returns "87" for URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Use the ID to fetch hotel data or perform operations
  };

  // Rest of your page implementation
}
```

#### URL Format

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Programmatic Navigation

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Query Parameters

For optional parameters or when multiple dynamic values are needed:

#### URL Format

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Accessing Query Parameters

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Get all query parameters
    final params = queryParameters();

    // Access specific parameters
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Alternative access method
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Programmatic Navigation with Query Parameters

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Handling Deep Links

You can handle deep link events in your `RouteProvider`:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Handle deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Update the route stack for deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### Testing Deep Links

For development and testing, you can simulate deep link activation using ADB (Android) or xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Debugging Tips

- Print all parameters in your init method to verify correct parsing
- Test different URL formats to ensure your app handles them correctly
- Remember that query parameters are always received as strings, convert them to the appropriate type as needed

---

## Common Patterns

### Parameter Type Conversion

Since all URL parameters are passed as strings, you'll often need to convert them:

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Optional Parameters

Handle cases where parameters might be missing:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Load specific user profile
} else {
  // Load current user profile
}

// Or check hasQueryParameter
if (hasQueryParameter('status')) {
  // Do something with the status parameter
} else {
  // Handle absence of the parameter
}
```


<div id="advanced"></div>

## Advanced

### Checking if Route Exists

You can check if a route is registered in your router:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter Methods

The `NyRouter` class provides several useful methods:

| Method | Description |
|--------|-------------|
| `getRegisteredRouteNames()` | Get all registered route names as a list |
| `getRegisteredRoutes()` | Get all registered routes as a map |
| `containsRoutes(routes)` | Check if router contains all specified routes |
| `getInitialRouteName()` | Get the initial route name |
| `getAuthRouteName()` | Get the authenticated route name |
| `getUnknownRouteName()` | Get the unknown/404 route name |

### Getting Route Arguments

You can get route arguments using `NyRouter.args<T>()`:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Get typed arguments
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument and NyQueryParameters

Data passed between routes is wrapped in these classes:

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
