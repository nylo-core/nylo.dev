# Navigation Hub

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
  - [Bottom Navigation Bar](#bottom-navigation-bar "Bottom Navigation Bar")
  - [Top Navigation Bar](#bottom-navigation-bar "Bottom Navigation Bar")
- [Navigating within a tab](#navigating-within-a-tab "Navigating within a tab")
- [Tabs](#tabs "Tabs")
  - [Adding Badges to Tabs](#adding-badges-to-tabs "Adding Badges to Tabs")
  - [Adding Alerts to Tabs](#adding-alerts-to-tabs "Adding Alerts to Tabs")
- [Maintaining state](#maintaining-state "Maintaining state")
- [State Actions](#state-actions "State Actions")
- [Loading Style](#loading-style "Loading Style")
- [Creating a Navigation Hub](#creating-a-navigation-hub "Creating a Navigation Hub")

<a name="introduction"></a>
<br>

## Introduction

Navigation Hubs are a central place where you can **manage** the navigation for all your tabs. 
Out the box you can create bottom navigation and top navigation layouts in seconds.

Let's **imagine** you have an app and you want to add a bottom navigation bar and allow users to navigate between different tabs in your app.

You can use a Navigation Hub to build this.

Let's dive into how you can use a Navigation Hub in your app.

<a name="basic-usage"></a>
<br>

## Basic Usage

You can create a Navigation Hub using the below command.

``` dart
dart run nylo_framework:main make:navigation_hub base
// or with Metro
metro make:navigation_hub base
```

This will create a **base_navigation_hub.dart** file in your `resources/pages/` directory.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());
  
  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

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
```

You can see that the Navigation Hub has **two** tabs, Home and Settings.

You can add more tabs by adding NavigationTab's to the Navigation Hub.

This newly created Navigation Hub will be added to your `routes/router.dart` file automatically.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initalRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

There's a lot **more** you can do with a Navigation Hub, let's dive into some of the features.

<a name="bottom-navigation-bar"></a>
<br>

### Bottom Navigation Bar

You can change the layout to a bottom navigation bar by setting the **layout** to use `NavigationHubLayout.bottomNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

You can customize the bottom navigation bar by setting properties like the following:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // elevation: 10,
        // type: BottomNavigationBarType.fixed,
        // backgroundColor: Colors.white,
        // iconSize: 24.0,
        // selectedItemColor: Colors.blue,
        // unselectedItemColor: Colors.grey,
        // selectedFontSize: 14.0,
        // unselectedFontSize: 12.0,
        // selectedLabelStyle: TextStyle(color: Colors.blue),
        // unselectedLabelStyle: TextStyle(color: Colors.grey),
        // showSelectedLabels: true,
        // showUnselectedLabels: true,
        // mouseCursor: SystemMouseCursors.click,
        // enableFeedback: true,
        // landscapeLayout: NavigationHubLandscapeLayout.centered,
        // useLegacyColorScheme: true,
    );
```

<a name="top-navigation-bar"></a>
<br>

### Top Navigation Bar

You can change the layout to a top navigation bar by setting the **layout** to use `NavigationHubLayout.topNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

You can customize the top navigation bar by setting properties like the following:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // isScrollable: false,
        // padding: EdgeInsets.all(8.0),
        // indicatorColor: Colors.blue,
        // automaticIndicatorColorAdjustment: true,
        // indicatorWeight: 2.0,
        // indicatorPadding: EdgeInsets.zero,
        // indicator: BoxDecoration(),
        // indicatorSize: TabBarIndicatorSize.tab,
        // dividerColor: Colors.grey,
        // dividerHeight: 1.0,
        // backgroundColor: Colors.white,
        // labelColor: Colors.black,
        // labelStyle: TextStyle(fontSize: 14.0),
        // labelPadding: EdgeInsets.all(8.0),
        // unselectedLabelColor: Colors.grey,
        // unselectedLabelStyle: TextStyle(fontSize: 12.0),
        // showSelectedLabels: true,
        // dragStartBehavior: DragStartBehavior.start,
        // overlayColor: Colors.transparent,
        // mouseCursor: SystemMouseCursors.click,
        // enableFeedback: true,
        // physics: BouncingScrollPhysics(),
        // splashFactory: InkRipple.splashFactory,
        // splashBorderRadius: BorderRadius.zero,
        // tabAlignment: TabAlignment.center,
        // textScaler: 1.0,
        // animationDuration: Duration(milliseconds: 200),
        // overlayColorState: Colors.transparent,
    );
```
<a name="navigating-within-a-tab"></a>
<br>

## Navigating to widgets within a tab

You can navigate to widgets within a tab by using the `pushTo` helper.

Inside your tab, you can use the `pushTo` helper to navigate to another widget.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

You can also pass data to the widget you're navigating to.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<a name="tabs"></a>
<br>

## Tabs

Tabs are the main building blocks of a Navigation Hub.

You can add tabs to a Navigation Hub by using the `NavigationTab` class.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ... 
    _MyNavigationHubState() : super(() async {
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
```

In the above example, we've added two tabs to the Navigation Hub, Home and Settings.

You can use different kinds of tabs like `NavigationTab`, `NavigationTab.badge`, and `NavigationTab.alert`.

- The `NavigationTab.badge` class is used to add badges to tabs.
- The `NavigationTab.alert` class is used to add alerts to tabs.
- The `NavigationTab` class is used to add a normal tab.

<a name="adding-badges-to-tabs"></a>
<br>

## Adding Badges to Tabs

We've made it easy to add badges to your tabs.

Badges are a great way to show users that there's something new in a tab.

Example, if you have a chat app, you can show the number of unread messages in the chat tab.

To add a badge to a tab, you can use the `NavigationTab.badge` class.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ... 
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

In the above example, we've added a badge to the Chat tab with an initial count of 10.

You can also update the badge count programmatically.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

By default, the badge count will be remembered. If you want to **clear** the badge count each session, you can set `rememberCount` to `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ... 
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
                rememberCount: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

<a name="adding-alerts-to-tabs"></a>
<br>

## Adding Alerts to Tabs

You can add alerts to your tabs.

Sometime you might not want to show a badge count, but you want to show an alert to the user.

To add an alert to a tab, you can use the `NavigationTab.alert` class.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ... 
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.alert(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                alertColor: Colors.red,
                alertEnabled: true,
                rememberAlert: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

This will add an alert to the Chat tab with a red color.

You can also update the alert programmatically.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<a name="maintaining-state"></a>
<br>

## Maintaining state

By default, the state of the Navigation Hub is maintained.

This means that when you navigate to a tab, the state of the tab is preserved.

If you want to clear the state of the tab each time you navigate to it, you can set `maintainState` to `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    @override
    bool get maintainState => false;
    ... 
}
```

<a name="state-actions"></a>
<br>

## State Actions

State actions are a way to interact with the Navigation Hub from anywhere in your app.

Here are some of the state actions you can use:

``` dart
  /// Reset the tab
  /// E.g. MyNavigationHub.stateActions.resetTabState(tab: 0);
  resetTabState({required tab});

  /// Update the badge count
  /// E.g. MyNavigationHub.updateBadgeCount(tab: 0, count: 2);
  updateBadgeCount({required int tab, required int count});

  /// Increment the badge count
  /// E.g. MyNavigationHub.incrementBadgeCount(tab: 0);
  incrementBadgeCount({required int tab});

  /// Clear the badge count
  /// E.g. MyNavigationHub.clearBadgeCount(tab: 0);
  clearBadgeCount({required int tab});
```

To use a state action, you can do the following:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// or
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<a name="loading-style"></a>
<br>

## Loading Style

Out the box, the Navigation Hub will show your **default** loading Widget (resources/widgets/loader_widget.dart) when the tab is loading.

You can customize the `loadingStyle` to update the loading style.

Here's a table for the different loading styles you can use:
// normal, skeletonizer, none

| Style | Description |
| --- | --- |
| normal | Default loading style |
| skeletonizer | Skeleton loading style |
| none | No loading style |

You can change the loading style like this:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

If you want to update the loading Widget in one of the styles, you can pass a `child` to the `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Now, when the tab is loading, the text "Loading..." will be displayed.

Example below:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
     _BaseNavigationHubState() : super(() async {
      
      await sleep(3); // simulate loading for 3 seconds

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

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ... 
}
```

<a name="creating-a-navigation-hub"></a>
<br>

## Creating a Navigation Hub

To create a Navigation Hub, you can use [Metro](/docs/{{$version}}/metro), use the below command.

``` bash
metro make:navigation_hub base
```

This will create a base_navigation_hub.dart file in your `resources/pages/` directory and add the Navigation Hub to your `routes/router.dart` file.
