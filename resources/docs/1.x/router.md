# Router

---

- <span class="text-grey">Sponsors</span>
- [Become a sponsor](https://nylo.dev/contributions)

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Basics
  - [Adding routes](#adding-routes "Adding routes")
  - [Navigating to pages](#navigating-to-pages "Navigating to pages")
  - [Passing data to routes](#passing-data-to-routes "Passing data to routes")
  - [Page transitions](#page-transitions "Page transitions")
  - [Navigation types](#navigation-types "Navigation types")


<a name="introduction"></a>
<br>
## Introduction

Routes helps us navigate users around our apps. They provide a simple journey usually from the (`/`) index page. You can add routes in Nylo with the `lib/routers/router.dart` file. In this file, you’ll be able to assign the `name` of the route e.g. `“/settings”` and also the widget view you want to show.

You may also need to pass data from one view to the other and that’s also possible when navigating from a widget. We’ll dive deeper into how all this works in Nylo. 

<a name="adding-routes"></a>
<br>

## Adding routes

This is the most basic form of adding a new route to your project in the `/lib/routes/router.dart` file.

``` dart
buildRouter() => nyCreateRoutes((router) {
  ...
  router.route("/settings-page", (context) => SettingsPage());

  // add more routes
  // router.route('/home', (context) => HomePage());

});
```

> {info} Inside the `router.dart` file you'll find the `buildRouter` function, this is called when initializing the app.

<a name="navigating-to-pages"></a>
<br>

## Navigating to pages

You can navigate to new pages using the `Navigator` class as per the below example.

``` dart
void _pressedSettings() {
    Navigator.pushNamed(context, "/settings-page");
}
```

You can also navigate using the `routeTo()` helper if your widget extends `NyState`.

``` dart
...
class SettingsPage extends NyStatefulWidget {
  final SettingController controller = SettingController();
  
  SettingsPage({Key key}) : super(key: key);
  
  @override
  _SettingsPageState createState() => _SettingsPageState();
}

class _SettingsPageState extends NyState<SettingsPage> {

  void _pressedSettings() {
      routeTo("/settings-page");
  }
```

Pass data to the next page.
``` dart
// HomePage Widget
void _pressedSettings() {
    Navigator.pushNamed(context, "/settings-page", arguments: "Hello World");
    // or
    routeTo("/settings-page", data: "Hello World");
}
...
// SettingsPage Widget
class _SettingsPageState extends NyState<SettingsPage> {
  ...
  @override
  widgetDidLoad() async {
    print(widget.data()); // Hello World
  }
```

Once you're on the new page, you can also call `pop()` to go back to the existing Page.
``` dart
// SettingsPage Widget
class _SettingsPageState extends NyState<SettingsPage> {
  ...
  _back() {
    this.pop();
  }
```

<a name="passing-data-to-routes"></a>
<br>

## Passing data to routes

You may sometimes need to pass data from one screen to another. Here’s a simple example of how that might look in Nylo.

``` dart
class _HomePageState extends NyState<HomePage> {
  ...
  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo("/profile-page", data: user);
  }

...
```

Next on our other page

``` dart 
class _ProfilePageState extends NyState<ProfilePage> {
  ...
  @override
  widgetDidLoad() {
    User user = widget.data();
    print(user.firstName); // Anthony

  }
```

Note: For this to work your widget will **need** to extend the `StatefulPageWidget` class and have a controller.