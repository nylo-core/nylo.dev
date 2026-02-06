# Navigation Hub

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
  - [Creating a Navigation Hub](#creating-a-navigation-hub "Creating a Navigation Hub")
  - [Creating Navigation Tabs](#creating-navigation-tabs "Creating Navigation Tabs")
  - [Bottom Navigation](#bottom-navigation "Bottom Navigation")
    - [Custom Nav Bar Builder](#custom-nav-bar-builder "Custom Nav Bar Builder")
  - [Top Navigation](#top-navigation "Top Navigation")
  - [Journey Navigation](#journey-navigation "Journey Navigation")
    - [Progress Styles](#journey-progress-styles "Journey Progress Styles")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState Helper Methods](#journey-state-helper-methods "JourneyState Helper Methods")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Navigating within a tab](#navigating-within-a-tab "Navigating within a tab")
- [Tabs](#tabs "Tabs")
  - [Adding Badges to Tabs](#adding-badges-to-tabs "Adding Badges to Tabs")
  - [Adding Alerts to Tabs](#adding-alerts-to-tabs "Adding Alerts to Tabs")
- [Initial Index](#initial-index "Initial Index")
- [Maintaining state](#maintaining-state "Maintaining state")
- [onTap](#on-tap "onTap")
- [State Actions](#state-actions "State Actions")
- [Loading Style](#loading-style "Loading Style")

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

The command will walk you through an interactive setup:

1. **Choose a layout type** - Select between `navigation_tabs` (bottom navigation) or `journey_states` (sequential flow).
2. **Enter tab/state names** - Provide comma-separated names for your tabs or journey states.

This will create files under your `resources/pages/navigation_hubs/base/` directory:
- `base_navigation_hub.dart` - The main hub widget
- `tabs/` or `states/` - Contains the child widgets for each tab or journey state

Here's what a generated Navigation Hub looks like:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

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

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

You can see that the Navigation Hub has **two** tabs, Home and Settings.

The `layout` method returns the layout type for the hub. It receives a `BuildContext` so you can access theme data and media queries when configuring your layout.

You can create more tabs by adding `NavigationTab`'s to the Navigation Hub.

First, you need to create a new widget using Metro.

``` bash
metro make:stateful_widget news_tab
```

You can also create multiple widgets at once.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Then, you can add the new widget to the Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

To use the Navigation Hub, add it to your router as the initial route:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

There's a lot **more** you can do with a Navigation Hub, let's dive into some of the features.

<div id="bottom-navigation"></div>

### Bottom Navigation

You can set the layout to a bottom navigation bar by returning `NavigationHubLayout.bottomNav` from the `layout` method.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

You can customize the bottom navigation bar by setting properties like the following:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

You can apply a preset style to your bottom navigation bar using the `style` parameter.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Custom Nav Bar Builder

For complete control over your navigation bar, you can use the `navBarBuilder` parameter.

This allows you to build any custom widget while still receiving the navigation data.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
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

You can change the layout to a top navigation bar by returning `NavigationHubLayout.topNav` from the `layout` method.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

You can customize the top navigation bar by setting properties like the following:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Journey Navigation

You can change the layout to a journey navigation by returning `NavigationHubLayout.journey` from the `layout` method.

This is great for onboarding flows or multi-step forms.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

You can also set a `backgroundGradient` for the journey layout:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **Note:** When `backgroundGradient` is set, it takes precedence over `backgroundColor`.

If you want to use the journey navigation layout, your **widgets** should use `JourneyState` as it contains a lot of helper methods to help you manage the journey.

You can create the whole journey using the `make:navigation_hub` command with the `journey_states` layout:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

This will create the hub and all journey state widgets under `resources/pages/navigation_hubs/onboarding/states/`.

Or you can create individual journey widgets using:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

You can then add the new widgets to the Navigation Hub.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Journey Progress Styles

You can customize the progress indicator style by using the `JourneyProgressStyle` class.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

You can use the following progress indicators:

- `JourneyProgressIndicator.none()`: Renders nothing - useful for hiding the indicator on a specific tab.
- `JourneyProgressIndicator.linear()`: Linear progress bar.
- `JourneyProgressIndicator.dots()`: Dots-based progress indicator.
- `JourneyProgressIndicator.numbered()`: Numbered step progress indicator.
- `JourneyProgressIndicator.segments()`: Segmented progress bar style.
- `JourneyProgressIndicator.circular()`: Circular progress indicator.
- `JourneyProgressIndicator.timeline()`: Timeline-style progress indicator.
- `JourneyProgressIndicator.custom()`: Custom progress indicator using a builder function.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

You can customize the progress indicator position and padding within the `JourneyProgressStyle`:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
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
_MyNavigationHubState() : super(() => {
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
});
```

<div id="journey-state"></div>

### JourneyState

The `JourneyState` class extends `NyState` with journey-specific functionality to make it easier to create onboarding flows and multi-step journeys.

To create a new `JourneyState`, you can use the below command.

``` bash
metro make:journey_widget onboard_user_dob
```

Or if you want to create multiple widgets at once, you can use the following command.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Here's what a generated JourneyState widget looks like:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

You'll notice that the **JourneyState** class uses `nextStep` to navigate forward and `onBackPressed` to go back.

The `nextStep` method runs through the full validation lifecycle: `canContinue()` -> `onBeforeNext()` -> navigate (or `onComplete()` if at last step) -> `onAfterNext()`.

You can also use `buildJourneyContent` to build a structured layout with optional navigation buttons:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

Here are the properties you can use in the `buildJourneyContent` method.

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

The `JourneyState` class has helper methods and properties that you can use to customize the behavior of your journey.

| Method / Property | Description |
| --- | --- |
| [`nextStep()`](#next-step) | Navigate to the next step with validation. Returns `Future<bool>`. |
| [`previousStep()`](#previous-step) | Navigate to the previous step. Returns `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Simple helper for navigating to the previous step. |
| [`onComplete()`](#on-complete) | Called when the journey is complete (at the last step). |
| [`onBeforeNext()`](#on-before-next) | Called before navigating to the next step. |
| [`onAfterNext()`](#on-after-next) | Called after navigating to the next step. |
| [`canContinue()`](#can-continue) | Validation check before navigating to the next step. |
| [`isFirstStep`](#is-first-step) | Returns true if this is the first step in the journey. |
| [`isLastStep`](#is-last-step) | Returns true if this is the last step in the journey. |
| [`currentStep`](#current-step) | Returns the current step index (0-based). |
| [`totalSteps`](#total-steps) | Returns the total number of steps. |
| [`completionPercentage`](#completion-percentage) | Returns the completion percentage (0.0 to 1.0). |
| [`goToStep(int index)`](#go-to-step) | Jump to a specific step by index. |
| [`goToNextStep()`](#go-to-next-step) | Jump to the next step (no validation). |
| [`goToPreviousStep()`](#go-to-previous-step) | Jump to the previous step (no validation). |
| [`goToFirstStep()`](#go-to-first-step) | Jump to the first step. |
| [`goToLastStep()`](#go-to-last-step) | Jump to the last step. |
| [`exitJourney()`](#exit-journey) | Exit the journey by popping the root navigator. |
| [`resetCurrentStep()`](#reset-current-step) | Reset the current step's state. |
| [`onJourneyComplete`](#on-journey-complete) | Callback when the journey completes (override in last step). |
| [`buildJourneyPage()`](#build-journey-page) | Build a full-screen journey page with Scaffold. |


<div id="next-step"></div>

#### nextStep

The `nextStep` method navigates to the next step with full validation. It runs through the lifecycle: `canContinue()` -> `onBeforeNext()` -> navigate or `onComplete()` -> `onAfterNext()`.

You can pass `force: true` to bypass validation and directly navigate.

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
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

To skip validation:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

The `previousStep` method navigates to the previous step. Returns `true` if successful, `false` if already at the first step.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

The `onBackPressed` method is a simple helper that calls `previousStep()` internally.

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
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

The `onComplete` method is called when `nextStep()` is triggered on the last step (after validation passes).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

The `onBeforeNext` method is called before navigating to the next step.

E.g. if you want to save data before navigating to the next step, you can do it here.

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

The `onAfterNext` method is called after navigating to the next step.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

The `canContinue` method is called when `nextStep()` is triggered. Return `false` to prevent navigation.

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

The `isFirstStep` property returns true if this is the first step in the journey.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

The `isLastStep` property returns true if this is the last step in the journey.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

The `currentStep` property returns the current step index (0-based).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

The `totalSteps` property returns the total number of steps in the journey.

<div id="completion-percentage"></div>

#### completionPercentage

The `completionPercentage` property returns the completion percentage as a value from 0.0 to 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

The `goToStep` method jumps directly to a specific step by index. This does **not** trigger validation.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

The `goToNextStep` method jumps to the next step without validation. If already at the last step, it does nothing.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

The `goToPreviousStep` method jumps to the previous step without validation. If already at the first step, it does nothing.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

The `goToFirstStep` method jumps to the first step.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

The `goToLastStep` method jumps to the last step.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

The `exitJourney` method exits the journey by popping the root navigator.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

The `resetCurrentStep` method resets the current step's state.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

The `onJourneyComplete` getter can be overridden in the **last step** of your journey to define what happens when the user completes the flow.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

The `buildJourneyPage` method builds a full-screen journey page wrapped in a `Scaffold` with `SafeArea`.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| Property | Type | Description |
| --- | --- | --- |
| `content` | `Widget` | The main content of the page. |
| `nextButton` | `Widget?` | The next button widget. |
| `backButton` | `Widget?` | The back button widget. |
| `contentPadding` | `EdgeInsetsGeometry` | The padding for the content. |
| `header` | `Widget?` | The header widget. |
| `footer` | `Widget?` | The footer widget. |
| `backgroundColor` | `Color?` | The background color of the Scaffold. |
| `appBar` | `Widget?` | An optional AppBar widget. |
| `crossAxisAlignment` | `CrossAxisAlignment` | The cross axis alignment of the content. |

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

You can add tabs to a Navigation Hub using the `NavigationTab` class and its named constructors.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

In the above example, we've added two tabs to the Navigation Hub, Home and Settings.

You can use different kinds of tabs:

- `NavigationTab.tab()` - A standard navigation tab.
- `NavigationTab.badge()` - A tab with a badge count.
- `NavigationTab.alert()` - A tab with an alert indicator.
- `NavigationTab.journey()` - A tab for journey navigation layouts.

<div id="adding-badges-to-tabs"></div>

## Adding Badges to Tabs

We've made it easy to add badges to your tabs.

Badges are a great way to show users that there's something new in a tab.

Example, if you have a chat app, you can show the number of unread messages in the chat tab.

To add a badge to a tab, you can use the `NavigationTab.badge` constructor.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
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
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## Adding Alerts to Tabs

You can add alerts to your tabs.

Sometimes you might not want to show a badge count, but you want to show an alert indicator to the user.

To add an alert to a tab, you can use the `NavigationTab.alert` constructor.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
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

<div id="initial-index"></div>

## Initial Index

By default, the Navigation Hub starts on the first tab (index 0). You can change this by overriding the `initialIndex` getter.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
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

<div id="on-tap"></div>

## onTap

You can override the `onTap` method to add custom logic when a tab is tapped.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## State Actions

State actions are a way to interact with the Navigation Hub from anywhere in your app.

Here are the state actions you can use:

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

To use a state action, you can do the following:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Loading Style

Out the box, the Navigation Hub will show your **default** loading Widget (resources/widgets/loader_widget.dart) when the tab is loading.

You can customize the `loadingStyle` to update the loading style.

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
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
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

The command will guide you through an interactive setup where you can choose the layout type and define your tabs or journey states.

This will create a `base_navigation_hub.dart` file in your `resources/pages/navigation_hubs/base/` directory with child widgets organized in `tabs/` or `states/` subfolders.
