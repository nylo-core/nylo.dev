# Router

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Basics
  - [Adding routes](#adding-routes "Adding routes")
  - [Navigating to pages](#navigating-to-pages "Navigating to pages")
  - [Multiple routers](#add-multiple-routers "Multiple routers")
- Features
  - [Passing data to another page](#passing-data-to-another-page "Passing data to another page")
  - [Query Parameters](#query-parameters "Query Parameters")
  - [Page transitions](#page-transitions "Page transitions")
  - [Navigation types](#navigation-types "Navigation types")
  - [Navigating back](#navigating-back "Navigating back")


<div id="introduction"></div>
## Introduction

Routes help us navigate users around our apps. They provide a journey usually from the (`/`) index page.

You can add routes inside the `lib/routers/router.dart` file. Routes are built with a route name e.g. `“/settings”` and then provide the widget that you want to show.


``` dart
appRouter() => nyRoutes((router) {
  ...
  router.route("/settings", (context) => SettingsPage());

  // add more routes
  // router.route('/home', (context) => HomePage());

});
```

You may also need to pass data from one view to another. In {{ config('app.name') }}, that’s possible using the `NyStatefulWidget`. We’ll dive deeper into this to explain how it works.


<div id="adding-routes"></div>

## Adding routes

Here's the most basic form of adding a new route to your project.

File: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.route("/settings-page", (context) => SettingsPage());

  // My new route
  router.route('/home', (context) => HomePage());

});
```

<div id="navigating-to-pages"></div>

## Navigating to pages

You can navigate to new pages using the `Navigator` class like in the example below.

``` dart
void _pressedSettings() {
    Navigator.pushNamed(context, "/settings-page");
}
```

You can also navigate using the `routeTo()` helper if your widget extends the `NyState` class.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class SettingsPage extends StatefulWidget {
  @override
  _SettingsPageState createState() => _SettingsPageState();
}

class _SettingsPageState extends NyState<SettingsPage> {

  void _pressedSettings() {
      routeTo("/settings-page");
  }
...
```

<div id="add-multiple-routers"></div>

## Multiple routers

If your `routes/router.dart` file is getting big or you need to separate your routes, you can. First, define your routes in a new file like the below example.

<br>

#### Example new routes file: `/lib/routes/dashboard_router.dart`

``` dart 
NyRouter dashboardRouter() => nyRoutes((router) {
   
   Add your routes here
   router.route("/account", (context) => AccountPage());
   
   router.route("/account/update", (context) => AccountUpdatePage());
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

<div id="passing-data-to-another-page"></div>

## Passing data to another page

In this section, we'll show how you can pass data from one widget to another.
At times it can be useful to send data using the `Navigator` class but you can use the `routeTo` helper too.

``` dart
// HomePage Widget
void _pressedSettings() {
    Navigator.pushNamed(context, "/settings-page", arguments: "Hello World");
    // or
    routeTo("/settings-page", data: "Hello World");
}
...
// SettingsPage Widget (other page)
class _SettingsPageState extends NyState<SettingsPage> {
  ...
  @override
  init() async {
    print(widget.data()); // Hello World
  }
```

More examples

``` dart
// Home page widget
class _HomePageState extends NyState<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo("/profile-page", data: user);
  }

...

// Profile page widget (other page)
class _ProfilePageState extends NyState<ProfilePage> {

  @override
  init() {
    User user = widget.data();
    print(user.firstName); // Anthony

  }
```

<div id="query-parameters"></div>

## Query Parameters

When navigating to a new page, you can provide query parameters. Let's take a look.

```dart
  // Home page
  Navigator.pushNamed(context, '/profile?user=7'); // navigate to profile

  ...

  // Profile Page
  @override
  init() async {
    print(widget.queryParameters()); // {"user": 7}
  }
```
As long as your page widget extends the `NyStatefulWidget` and `NyState` class then you can call `widget.queryParameters()` to fetch all the query parameters from the route name.

```dart 
// Example page
Navigator.pushNamed(context, '/home?hello=world&say=I%20love%20code');
...

// Home page
class MyHomePage extends NyStatefulWidget {
  ...
}

class _MyHomePageState extends NyState<MyHomePage> {

  @override
  init() async {
    print(widget.queryParameters()); // {"hello": "World", "say": "I love code"}
  }
```

> Query parameters must follow the HTTP protocol E.g. /account?userId=1&tab=2

<div id="page-transitions"></div>

## Page Transitions

You can add transitions when you navigate from one page by modifying your `router.dart` file.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.route(
    "/settings-page", (context) => SettingsPage(), 
    transition: PageTransitionType.bottomToTop
    );

  // leftToRightWithFade
  router.route(
    '/home', (context) => HomePage(), 
    transition: PageTransitionType.leftToRightWithFade
  );

});
```

Available transitions:
- PageTransitionType.fade
- PageTransitionType.rightToLeft
- PageTransitionType.leftToRight
- PageTransitionType.topToBottom
- PageTransitionType.bottomToTop
- PageTransitionType.scale (with alignment)
- PageTransitionType.rotate (with alignment)
- PageTransitionType.size (with alignment)
- PageTransitionType.rightToLeftWithFade
- PageTransitionType.leftToRightWithFade
- PageTransitionType.leftToRightJoined
- PageTransitionType.rightToLeftJoined

You can also apply a transition when navigating to a new page in your project.

``` dart
// Home page widget
class _HomePageState extends NyState<HomePage> {

  _showProfile() {
    routeTo(
      "/profile-page", 
      pageTransition: PageTransitionType.bottomToTop
    );
  }
...
```

{{ config('app.name') }} uses the <a href="https://pub.dev/packages/page_transition" target="_BLANK">page_transition</a> under the hood to make this possible.

<div id="navigation-types"></div>

## Navgiation Types

When navigating you can specify one of the following if you are using the `routeTo` helper.

- **NavigationType.push** - push a new page to your apps' route stack.
- **NavigationType.pushReplace** - Replace the current route which disposes of the previous route once the new route has finished.
- **NavigationType.popAndPushNamed** - Pop the current route off the navigator and push a named route in its place.
- **NavigationType.pushAndForgetAll** - push to a new page and dispose any other pages on the stack.

``` dart
// Home page widget
class _HomePageState extends NyState<HomePage> {

  _showProfile() {
    routeTo(
      "/profile-page", 
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
class _SettingsPageState extends NyState<SettingsPage> {

  _back() {
    this.pop();
    // or 
    Navigator.pop(context);
  }
...
```

If you want to return a value to the previous widget, provide a `result` like in the below example.

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyState<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Then get the value from the widget before it using the `onPop` parameter
// HomePage Widget
class _HomePageState extends NyState<HomePage> {

  _viewSettings() {
    routeTo('/settings', onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...


```
