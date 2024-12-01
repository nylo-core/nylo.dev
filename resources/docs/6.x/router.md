# Router

---

<a name="section-1"></a>

- [Introduction](#introduction "Introduction")
- Basics
  - [Adding routes](#adding-routes "Adding routes")
  - [Navigating to pages](#navigating-to-pages "Navigating to pages")
  - [Initial route](#initial-route "Initial route")
- Sending data to another page
  - [Passing data to another page](#passing-data-to-another-page "Passing data to another page")
- Authentication
    - [Authenticated route](#authenticated-route "Authenticated route")
- Navigation
    - [Navigation types](#navigation-types "Navigation types")
    - [Navigating back](#navigating-back "Navigating back")
    - [Page transitions](#page-transitions "Page transitions")
    - [Route History](#route-history "Route History")
- Route parameters
    - [Using Route Parameters](#route-parameters "Route Parameters")
    - [Query Parameters](#query-parameters "Query Parameters")
- [Route Groups](#route-groups "Route Groups")
- [Multiple routers](#add-multiple-routers "Multiple routers")
- [Route Guards](#route-guards "Route Guards")
- [Deep linking](#deep-linking "Deep linking")



<a name="introduction"></a>
<br>
## Introduction

Routes guide users to different pages in our app. 

You can add routes inside the `lib/routers/router.dart` file. 

``` dart
appRouter() => nyRoutes((router) {
  
  router.add(HomePage.path).initialRoute();
  
  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> You can create your routes manually or use the <a href="/docs/{{ $version }}/metro">Metro</a> CLI tool to create them for you.

Here's an example of creating an 'account' page using Metro.

``` bash 
# Run this command in your terminal
dart run nylo_framework:main make:page account_page
```

``` dart
// Adds your new route automatically to /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

You may also need to pass data from one view to another. In {{ config('app.name') }}, that’s possible using the `NyStatefulWidget`. We’ll dive deeper into this to explain how it works.


<a name="adding-routes"></a>
<br>

## Adding routes

This is the easiest way to add new routes to your project.

Run the below command to create a new page.

```dart
dart run nylo_framework:main make:page profile_page
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

<a name="navigating-to-pages"></a>
<br>

## Navigating to pages

You can navigate to new pages using the `routeTo` helper, here's an example`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<a name="add-multiple-routers"></a>
<br>

## Multiple routers

If your `routes/router.dart` file is getting big, or you need to separate your routes, you can. First, define your routes in a new file like the below example.

<br>

#### Example new routes file: `/lib/routes/dashboard_router.dart`

``` dart 
NyRouter dashboardRouter() => nyRoutes((router) {
   
   // example dashboard routes
   router.add(AccountPage.path);
   
   router.add(NotificationsPage.path);
});
```

Then, open `/lib/app/providers/route_provider.dart` and add the new router.

``` dart
import 'package:flutter_app/routes/router.dart';
import 'package:flutter_app/routes/dashboard_router.dart';
import 'package:nylo_framework/nylo_framework.dart';

class RouteProvider implements NyProvider {

  boot({{ config('app.name') }} nylo) async {
    nylo.addRouter(appRouter());

    nylo.addRouter(dashboardRouter()); // new routes

    return nylo;
  }
}

...
```

<a name="initial-route"></a>
<br>

## Initial route

In your routers, you can set a page to be the initial route by passing the `initialRoute` parameter to the route you want to use. 

Once you've set the initial route, it will be the first page that loads when you open the app.

``` dart 
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute(); 
  // new initial route
});
```

Or like this

``` dart 
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path, initialRoute: true);
});
```

<a name="route-guards"></a>
<br>

## Route guards

Route guards are used to protect your pages from unauthorized access.

To create a new Route Guard, run the below command.

``` bash
# Run this command in your terminal to create a new Route Guard
dart run nylo_framework:main make:route_guard dashboard
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

You can modify the `onRequest` method to suit your needs.

File: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  onRequest(PageRequest pageRequest) async {
    // Perform a check if they can access the page
    bool userLoggedIn = await Auth.isAuthenticated();
    
    if (userLoggedIn == false) {
      return redirectTo(LoginPage.path);
    }
    
    return pageRequest;
  }
}
```

You can also set route guards using the `routeGuard` extension helper like in the below example.

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

### Creating a route guard

You can create a new route guard using the <a href="/docs/6.x/metro">Metro</a> CLI.

```dart
dart run nylo_framework:main make:route_guard subscribed
```

<a name="passing-data-to-another-page"></a>
<br>

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

<a name="route-groups"></a>
<br>

## Route Groups

In {{ config('app.name') }}, you can create route groups to organize your routes.

Route groups are perfect for organizing your routes into categories, like 'auth' or 'admin'.

You can define a route group like in the below example.

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard"
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  }); 
```

#### Optional settings for route groups are:

- **route_guards** - This will apply all the route guards defined to the routes in the group. Learn more about route guards [here](#route-guards).

- **prefix** - This will add the prefix to all the routes in the group. E.g. `/dashboard/chat`, `/dashboard/followers`. Now anytime you navigate to a page in the group, it will use the prefix. E.g. `routeTo(ChatPage.path)` will navigate to `/dashboard/chat`.


<a name="route-parameters"></a>
<br>

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


<a name="query-parameters"></a>
<br>

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
As long as your page widget extends the `NyStatefulWidget` and `NyState` class, then you can call `widget.queryParameters()` to fetch all the query parameters from the route name.

```dart 
// Example page
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Home page
class MyHomePage extends NyStatefulWidget {
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

> Query parameters must follow the HTTP protocol, E.g. /account?userId=1&tab=2

<a name="page-transitions"></a>
<br>

## Page Transitions

You can add transitions when you navigate from one page by modifying your `router.dart` file.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transition: PageTransitionType.bottomToTop
  );

  // fade
  router.add(HomePage.path,
    transition: PageTransitionType.fade
  );

});
```

Available transitions:
- PageTransitionType.fade
- PageTransitionType.rightToLeft
- PageTransitionType.leftToRight
- PageTransitionType.topToBottom
- PageTransitionType.bottomToTop

You can also apply a transition when navigating to a new page in your project.

``` dart
// Home page widget
class _HomePageState extends NyState<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path, 
      pageTransition: PageTransitionType.bottomToTop
    );
  }
...
```

<a name="navigation-types"></a>
<br>

## Navigation Types

When navigating, you can specify one of the following if you are using the `routeTo` helper.

- **NavigationType.push** - push a new page to your apps' route stack.
- **NavigationType.pushReplace** - Replace the current route, which disposes of the previous route once the new route has finished.
- **NavigationType.popAndPushNamed** - Pop the current route off the navigator and push a named route in its place.
- **NavigationType.pushAndForgetAll** - push to a new page and dispose of any other pages on the route stack.

``` dart
// Home page widget
class _HomePageState extends NyState<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path, 
      navigationType: NavigationType.pushReplace
    );
  }
...
```

<a name="navigating-back"></a>
<br>

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

<a name="authenticated-route"></a>
<br>

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

You can also navigate to the authenticated page using the `routeTo` helper.

``` dart
routeToAuthenticatedRoute();
```

Learn more about authentication [here](/docs/{{ $version }}/authentication).

<a name="route-history"></a>
<br>

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

<a name="deep-linking"></a>
<br>

## Deep linking

Deep linking allows you to navigate to a specific page in your app using a URL.

First, ensure you have deep linking enabled in your project.

IOS - <a target="_BLANK" href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#2-adjust-ios-build-settings">Link</a>

Android - <a target="_BLANK" href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml">Link</a>

Next, ensure you have some routes in your project.

``` dart
// File: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  router.add(ProfilePage.path);

  router.add(SettingsPage.path);
});
```

Now you can navigate to any of the pages using a URL.

``` bash
// Example URL

https://<web domain>/profile - navigates to the profile page
https://<web domain>/settings - navigates to the settings page

// Passing parameters to the Page
https://<web domain>/profile?user=7 - navigates to the profile page with the user ID 7
```

You can also pass query parameters to the page using the URL.

Let's imagine you have a page that displays a user's profile. You can pass the user ID to the page using the URL.

`https://<web domain>/profile?user=20`

``` dart
// Profile Page
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(queryParameters()); // {"user": 20}
    
    print(widget.queryParameters()); // or like this {"user": 20}
  };
}
```

---
