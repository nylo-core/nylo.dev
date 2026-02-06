# Navigation Hub

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
  - [Creating a Navigation Hub](#creating-a-navigation-hub "Creating a Navigation Hub")
  - [Creating Navigation Tabs](#creating-navigation-tabs "Creating Navigation Tabs")
  - [Bottom Navigation](#bottom-navigation "Bottom Navigation")
    - [Bottom Nav Styles](#bottom-nav-styles "Bottom Nav Styles")
    - [Custom Nav Bar Builder](#custom-nav-bar-builder "Custom Nav Bar Builder")
  - [Top Navigation](#top-navigation "Top Navigation")
  - [Journey Navigation](#journey-navigation "Journey Navigation")
    - [Progress Styles](#journey-progress-styles "Journey Progress Styles")
    - [Button Styles](#journey-button-styles "Button Styles")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState Helper Methods](#journey-state-helper-methods "JourneyState Helper Methods")
- [Navigating within a tab](#navigating-within-a-tab "Navigating within a tab")
- [Tabs](#tabs "Tabs")
  - [Adding Badges to Tabs](#adding-badges-to-tabs "Adding Badges to Tabs")
  - [Adding Alerts to Tabs](#adding-alerts-to-tabs "Adding Alerts to Tabs")
- [Maintaining state](#maintaining-state "Maintaining state")
- [State Actions](#state-actions "State Actions")
- [Loading Style](#loading-style "Loading Style")
- [Creating a Navigation Hub](#creating-a-navigation-hub "Creating a Navigation Hub")

<div id="introduction"></div>

## Introduction

Navigation Hubs are a central place where you can **manage** the navigation for all your widgets. 
Out the box you can create bottom, top and journey navigation layouts in seconds.

Let's **imagine** you have an app and you want to add a bottom navigation bar and allow users to navigate between different tabs in your app.

You can use a Navigation Hub to build this.

Let's dive into how you can use a Navigation Hub in your app.

<div id="basic-usage"></div>

## Basic Usage

You can create a Navigation Hub using the below command.

``` bash
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
  /// - [NavigationHubLayout.journey] Journey navigation
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

You can create more tabs by adding NavigationTab's to the Navigation Hub.

First, you need to create a new widget using Metro.

``` bash
metro make:stateful_widget create_advert_tab
```

You can also create multiple widgets at once.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Then, you can add the new widget to the Navigation Hub.

``` dart
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
      2: NavigationTab(
         title: "News",
         page: NewsTab(),
         icon: Icon(Icons.newspaper),
         activeIcon: Icon(Icons.newspaper),
      ),
    };
  });

import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initalRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

There's a lot **more** you can do with a Navigation Hub, let's dive into some of the features.

<div id="bottom-navigation"></div>

### Bottom Navigation

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
        // customize the bottomNav layout properties
    );
```

<div id="bottom-nav-styles"></div>

### Bottom Nav Styles

You can apply preset styles to your bottom navigation bar using the `style` parameter.

Nylo provides several built-in styles:

- `BottomNavStyle.material()` - Default Flutter material style
- `BottomNavStyle.glass()` - iOS 26-style frosted glass effect with blur
- `BottomNavStyle.floating()` - Floating pill-style nav bar with rounded corners

#### Glass Style

The glass style creates a beautiful frosted glass effect, perfect for modern iOS 26-inspired designs.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

You can customize the glass effect:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.glass(
        blur: 15.0,                              // Blur intensity
        opacity: 0.7,                            // Background opacity
        borderRadius: BorderRadius.circular(20), // Rounded corners
        margin: EdgeInsets.all(16),              // Float above the edge
        backgroundColor: Colors.white.withValues(alpha: 0.8),
    ),
)
```

#### Floating Style

The floating style creates a pill-shaped nav bar that floats above the bottom edge.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

You can customize the floating style:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.floating(
        borderRadius: BorderRadius.circular(30),
        margin: EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        shadow: BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 10,
        ),
        backgroundColor: Colors.white,
    ),
)
```

<div id="custom-nav-bar-builder"></div>

### Custom Nav Bar Builder

For complete control over your navigation bar, you can use the `navBarBuilder` parameter.

This allows you to build any custom widget while still receiving the navigation data.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

The `NavBarData` object contains:

| Property | Type | Description |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | The navigation bar items |
| `currentIndex` | `int` | The currently selected index |
| `onTap` | `ValueChanged<int>` | Callback when a tab is tapped |

Here's an example of a fully custom glass nav bar:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **Note:** When using `navBarBuilder`, the `style` parameter is ignored.

<div id="top-navigation"></div>

### Top Navigation

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
        // customize the topNav layout properties
    );
```

<div id="journey-navigation"></div>

### Journey Navigation

You can change the layout to a journey navigation by setting the **layout** to use `NavigationHubLayout.journey`.

This is great for onboarding flows or multi-step forms.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // customize the journey layout properties
    );
```

If you want to use the journey navigation layout, your **widgets** should use `JourenyState` as it contains a lot of helper methods to help you manage the journey.

You can create a JourneyState using the below command.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
This will create the following files in your **resources/widgets/** directory `welcome.dart`, `phone_number_step.dart` and `add_photos_step.dart`.

You can then add the new widgets to the Navigation Hub.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

The journey navigation layout will automatically handle the back and next buttons for you if you define a `buttonStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

You can also customize the logic in your widgets.

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class WelcomeStep extends StatefulWidget {
  const WelcomeStep({super.key});

  @override
  createState() => _WelcomeStepState();
}

class _WelcomeStepState extends JourneyState<WelcomeStep> {
  _WelcomeStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeStep', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: onNextPressed,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
  }

  /// Check if the journey can continue to the next step
  /// Override this method to add validation logic
  Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
  }

  /// Called when unable to continue (canContinue returns false)
  /// Override this method to handle validation failures
  Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
  }

  /// Called before navigating to the next step
  /// Override this method to perform actions before continuing
  Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
  }

  /// Called after navigating to the next step
  /// Override this method to perform actions after continuing
  Future<void> onAfterNext() async {
    // print('Navigated to the next step');
  }

  /// Called when the journey is complete (at the last step)
  /// Override this method to perform completion actions
  Future<void> onComplete() async {}
}
```

You can override any of the methods in the `JourneyState` class.

<div id="journey-progress-styles"></div>

### Journey Progress Styles

You can customize the progress indicator style by using the `JourneyProgressStyle` class.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(
            activeColor: Colors.blue,
            inactiveColor: Colors.grey,
            thickness: 4.0,
        ),
    );
```

You can use the following progress styles:

- `JourneyProgressIndicator.none`: Renders nothing — useful for hiding the indicator on a specific tab.
- `JourneyProgressIndicator.linear`: Linear progress indicator.
- `JourneyProgressIndicator.dots`: Dots-based progress indicator.
- `JourneyProgressIndicator.numbered`: Numbered step progress indicator.
- `JourneyProgressIndicator.segments`: Segmented progress bar style.
- `JourneyProgressIndicator.circular`: Circular progress indicator.
- `JourneyProgressIndicator.timeline`: Timeline-style progress indicator.
- `JourneyProgressIndicator.custom`: Custom progress indicator using a builder function.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    );
```

You can customize the progress indicator position and padding within the `JourneyProgressStyle`:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.dots(),
            position: ProgressIndicatorPosition.bottom,
            padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
        ),
    );
```

You can use the following progress indicator positions:

- `ProgressIndicatorPosition.top`: Progress indicator at the top of the screen.
- `ProgressIndicatorPosition.bottom`: Progress indicator at the bottom of the screen.

#### Per-Tab Progress Style Override

You can override the layout-level `progressStyle` on individual tabs using `NavigationTab.journey(progressStyle: ...)`. Tabs without their own `progressStyle` inherit the layout default. Tabs with no layout default and no per-tab style will not show a progress indicator.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
            progressStyle: JourneyProgressStyle(
                indicator: JourneyProgressIndicator.numbered(),
            ), // overrides the layout default for this tab only
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

<div id="journey-button-styles">
<br>

### Journey Button Styles

If you want to build an onboarding flow, you can set the `buttonStyle` property in the `NavigationHubLayout.journey` class.

Out the box, you can use the following button styles:

- `JourneyButtonStyle.standard`: Standard button style with customizable properties.
- `JourneyButtonStyle.minimal`: Minimal button style with icons only.
- `JourneyButtonStyle.outlined`: Outlined button style.
- `JourneyButtonStyle.contained`: Contained button style.
- `JourneyButtonStyle.custom`: Custom button style using builder functions.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ... 
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(),
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

<div id="journey-state"></div>

### JourneyState

The `JourneyState` class contains a lot of helper methods to help you manage the journey.

To create a new `JourneyState`, you can use the below command.

``` bash
metro make:journey_widget onboard_user_dob
```

Or if you want to create multiple widgets at once, you can use the following command.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

This will create the following files in your **resources/widgets/** directory `welcome.dart`, `phone_number_step.dart` and `add_photos_step.dart`.

You can then add the new widgets to the Navigation Hub.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

If we look at the `WelcomeStep` class, we can see that it extends the `JourneyState` class.

``` dart
...
class _WelcomeTabState extends JourneyState<WelcomeTab> {
  _WelcomeTabState() : super(
      navigationHubState: BaseNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeTab', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
    );
  }
```

You'll notice that the **JourneyState** class will use `buildJourneyContent` to build the content of the page.

Here are a list of the properties you can use in the `buildJourneyContent` method.

| Property | Type | Description |
| --- | --- | --- |
| `content` | `Widget` | The main content of the page. |
| `nextButton` | `Widget?` | The next button widget. |
| `backButton` | `Widget?` | The back button widget. |
| `contentPadding` | `EdgeInsetsGeometry` | The padding for the content. |
| `header` | `Widget?` | The header widget. |
| `footer` | `Widget?` | The footer widget. |
| `crossAxisAlignment` | `CrossAxisAlignment` | The cross axis alignment of the content. |


<div id="journey-state-helper-methods"></div>

### JourneyState Helper Methods

The `JourneyState` class has some helper methods that you can use to customize the behavior of your journey.

| Method | Description |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | Called when the next button is pressed. |
| [`onBackPressed()`](#on-back-pressed) | Called when the back button is pressed. |
| [`onComplete()`](#on-complete) | Called when the journey is complete (at the last step). |
| [`onBeforeNext()`](#on-before-next) | Called before navigating to the next step. |
| [`onAfterNext()`](#on-after-next) | Called after navigating to the next step. |
| [`onCannotContinue()`](#on-cannot-continue) | Called when the journey cannot continue (canContinue returns false). |
| [`canContinue()`](#can-continue) | Called when the user tries to navigate to the next step. |
| [`isFirstStep`](#is-first-step) | Returns true if this is the first step in the journey. |
| [`isLastStep`](#is-last-step) | Returns true if this is the last step in the journey. |
| [`goToStep(int index)`](#go-to-step) | Navigate to the next step index. |
| [`goToNextStep()`](#go-to-next-step) | Navigate to the next step. |
| [`goToPreviousStep()`](#go-to-previous-step) | Navigate to the previous step. |
| [`goToFirstStep()`](#go-to-first-step) | Navigate to the first step. |
| [`goToLastStep()`](#go-to-last-step) | Navigate to the last step. |


<div id="on-next-pressed"></div>

#### onNextPressed

The `onNextPressed` method is called when the next button is pressed.

E.g. You can use this method to trigger the next step in the journey.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: onNextPressed, // this will attempt to navigate to the next step
        ),
    );
}
```

<div id="on-back-pressed"></div>

#### onBackPressed

The `onBackPressed` method is called when the back button is pressed.

E.g. You can use this method to trigger the previous step in the journey.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed, // this will attempt to navigate to the previous step
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

The `onComplete` method is called when the journey is complete (at the last step).

E.g. if the if this widget is the last step in the journey, this method will be called.

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

The `onBeforeNext` method is called before navigating to the next step.

E.g. if you want to save data before navigating to the next step, you can do it here.

``` dart
Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
}
```

<div id="is-first-step"></div>

#### isFirstStep

The `isFirstStep` method returns true if this is the first step in the journey.

E.g. You can use this method to disable the back button if this is the first step.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly( // Example of disabling the back button
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="is-last-step"></div>

#### isLastStep

The `isLastStep` method returns true if this is the last step in the journey.

E.g. You can use this method to disable the next button if this is the last step.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue", // Example updating the next button text
            onPressed: onNextPressed,
        ),
    );
}
```

<div id="go-to-step"></div>

#### goToStep

The `goToStep` method is used to navigate to a specific step in the journey.

E.g. You can use this method to navigate to a specific step in the journey.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Add photos"
            onPressed: () {
                goToStep(2); // this will navigate to the step with index 2
                // Note: this will not trigger the onNextPressed method
            }, 
        ),
    );
}
```

<div id="go-to-next-step"></div>

#### goToNextStep

The `goToNextStep` method is used to navigate to the next step in the journey.

E.g. You can use this method to navigate to the next step in the journey.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToNextStep(); // this will navigate to the next step
                // Note: this will not trigger the onNextPressed method
            }, 
        ),
    );
}
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

The `goToPreviousStep` method is used to navigate to the previous step in the journey.

E.g. You can use this method to navigate to the previous step in the journey.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: () {
                goToPreviousStep(); // this will navigate to the previous step
            }, 
        ),
    );
}
```

<div id="on-after-next"></div>

#### onAfterNext

The `onAfterNext` method is called after navigating to the next step.


E.g. if you want to perform some action after navigating to the next step, you can do it here.

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

The `onCannotContinue` method is called when the journey cannot continue (canContinue returns false).

E.g. if you want to show an error message when the user tries to navigate to the next step without filling in the required fields, you can do it here.

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

The `canContinue` method is called when the user tries to navigate to the next step.

E.g. if you want to perform some validation before navigating to the next step, you can do it here.

``` dart
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

The `goToFirstStep` method is used to navigate to the first step in the journey.


E.g. You can use this method to navigate to the first step in the journey.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToFirstStep(); // this will navigate to the first step
            }, 
        ),
    );
}
```

<div id="go-to-last-step"></div>

#### goToLastStep

The `goToLastStep` method is used to navigate to the last step in the journey.

E.g. You can use this method to navigate to the last step in the journey.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToLastStep(); // this will navigate to the last step
            }, 
        ),
    );
}
```

<div id="navigating-within-a-tab"></div>

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

<div id="tabs"></div>

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

<div id="adding-badges-to-tabs"></div>

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

<div id="adding-alerts-to-tabs"></div>

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

<div id="maintaining-state"></div>

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

<div id="state-actions"></div>

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

<div id="loading-style"></div>

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

<div id="creating-a-navigation-hub"></div>

## Creating a Navigation Hub

To create a Navigation Hub, you can use [Metro](/docs/{{$version}}/metro), use the below command.

``` bash
metro make:navigation_hub base
```

This will create a base_navigation_hub.dart file in your `resources/pages/` directory and add the Navigation Hub to your `routes/router.dart` file.
