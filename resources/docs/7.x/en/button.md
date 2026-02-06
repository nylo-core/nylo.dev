# Button

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Available Button Types](#button-types "Available Button Types")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Async Loading State](#async-loading "Async Loading State")
- [Animation Styles](#animation-styles "Animation Styles")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Splash Styles](#splash-styles "Splash Styles")
- [Loading Styles](#loading-styles "Loading Styles")
- [Form Submission](#form-submission "Form Submission")
- [Customizing Buttons](#customizing-buttons "Customizing Buttons")
- [Parameters Reference](#parameters "Parameters Reference")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} provides a `Button` class with eight pre-built button styles out of the box. Each button comes with built-in support for:

- **Async loading states** — return a `Future` from `onPressed` and the button automatically shows a loading indicator
- **Animation styles** — choose from clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph, and shake effects
- **Splash styles** — add ripple, highlight, glow, or ink touch feedback
- **Form submission** — wire a button directly to a `NyFormData` instance

You can find your app's button definitions in `lib/resources/widgets/buttons/buttons.dart`. This file contains a `Button` class with static methods for each button type, making it easy to customize the defaults for your project.

<div id="basic-usage"></div>

## Basic Usage

Use the `Button` class anywhere in your widgets. Here's a simple example inside a page:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Every button type follows the same pattern — pass a `text` label and an `onPressed` callback.

<div id="button-types"></div>

## Available Button Types

All buttons are accessed through the `Button` class using static methods.

<div id="primary"></div>

### Primary

A filled button with a shadow, using your theme's primary color. Best for main call-to-action elements.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

A filled button with a softer surface color and subtle shadow. Good for secondary actions alongside a primary button.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

A transparent button with a border stroke. Useful for less prominent actions or cancel buttons.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

You can customize the border and text colors:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

A minimal button with no background or border. Ideal for inline actions or links.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

You can customize the text color:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

A filled button that displays an icon alongside the text. The icon appears before the text by default.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

You can customize the background color:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

A button with a linear gradient background. Uses your theme's primary and tertiary colors by default.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

You can provide custom gradient colors:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

A pill-shaped button with fully rounded corners. The border radius defaults to half the button height.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

You can customize the background color and border radius:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

A frosted glass-style button with a backdrop blur effect. Works well when placed over images or colored backgrounds.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

You can customize the text color:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Async Loading State

One of the most powerful features of {{ config('app.name') }} buttons is **automatic loading state management**. When your `onPressed` callback returns a `Future`, the button will automatically display a loading indicator and disable interaction until the operation completes.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

While the async operation is running, the button will show a skeleton loading effect (by default). Once the `Future` completes, the button returns to its normal state.

This works with any async operation — API calls, database writes, file uploads, or anything that returns a `Future`:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

No need to manage `isLoading` state variables, call `setState`, or wrap anything in a `StatefulWidget` — {{ config('app.name') }} handles it all for you.

### How It Works

When the button detects that `onPressed` returns a `Future`, it uses the `lockRelease` mechanism to:

1. Show a loading indicator (controlled by `LoadingStyle`)
2. Disable the button to prevent duplicate taps
3. Wait for the `Future` to complete
4. Restore the button to its normal state

<div id="animation-styles"></div>

## Animation Styles

Buttons support press animations through `ButtonAnimationStyle`. These animations provide visual feedback when a user interacts with a button. You can set the animation style when customizing your buttons in `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

A Duolingo-style 3D press effect. The button translates downward on press and springs back on release. Best for primary actions and game-like UX.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Scales the button down on press and springs back on release. Best for add-to-cart, like, and favorite buttons.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

A subtle continuous scale pulse while the button is held down. Best for long-press actions or drawing attention.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Compresses the button horizontally and expands it vertically on press. Best for playful and interactive UIs.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

A wobbly elastic deformation effect. Best for fun, casual, or entertainment apps.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

A glossy highlight that sweeps across the button on press. Best for premium features or CTAs you want to draw attention to.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

An enhanced ripple effect that expands from the touch point. Best for Material Design emphasis.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

The button's border radius increases on press, creating a shape-shifting effect. Best for subtle, elegant feedback.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

A horizontal shake animation. Best for error states or invalid actions — shake the button to signal something went wrong.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Fine-tune the effect:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Disabling Animations

To use a button with no animation:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Changing the Default Animation

To change the default animation for a button type, modify your `lib/resources/widgets/buttons/buttons.dart` file:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Splash Styles

Splash effects provide visual touch feedback on buttons. Configure them through `ButtonSplashStyle`. Splash styles can be combined with animation styles for layered feedback.

### Available Splash Styles

| Splash | Factory | Description |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Standard Material ripple from touch point |
| Highlight | `ButtonSplashStyle.highlight()` | Subtle highlight without ripple animation |
| Glow | `ButtonSplashStyle.glow()` | Soft glow radiating from touch point |
| Ink | `ButtonSplashStyle.ink()` | Quick ink splash, faster and more responsive |
| None | `ButtonSplashStyle.none()` | No splash effect |
| Custom | `ButtonSplashStyle.custom()` | Full control over the splash factory |

### Example

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

You can customize splash colors and opacity:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Loading Styles

The loading indicator shown during async operations is controlled by `LoadingStyle`. You can set it per button type in your buttons file.

### Skeletonizer (Default)

Displays a shimmer skeleton effect over the button:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Shows a loading widget (defaults to the app loader):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Keeps the button visible but disables interaction during loading:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Form Submission

All buttons support the `submitForm` parameter, which connects the button to a `NyForm`. When tapped, the button will validate the form and call your success handler with the form data.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

The `submitForm` parameter accepts a record with two values:
1. A `NyFormData` instance (or form name as a `String`)
2. A callback that receives the validated data

By default, `showToastError` is `true`, which displays a toast notification when form validation fails. Set it to `false` to handle errors silently:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

When the `submitForm` callback returns a `Future`, the button will automatically show a loading state until the async operation completes.

<div id="customizing-buttons"></div>

## Customizing Buttons

All button defaults are defined in your project at `lib/resources/widgets/buttons/buttons.dart`. Each button type has a corresponding widget class in `lib/resources/widgets/buttons/partials/`.

### Changing Default Styles

To modify a button's default appearance, edit the `Button` class:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Customizing a Button Widget

To change the visual appearance of a button type, edit the corresponding widget in `lib/resources/widgets/buttons/partials/`. For example, to change the primary button's border radius or shadow:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Creating a New Button Type

To add a new button type:

1. Create a new widget file in `lib/resources/widgets/buttons/partials/` extending `StatefulAppButton`.
2. Implement the `buildButton` method.
3. Add a static method in the `Button` class.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Then register it in the `Button` class:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Parameters Reference

### Common Parameters (All Button Types)

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `text` | `String` | required | The button label text |
| `onPressed` | `VoidCallback?` | `null` | Callback when the button is tapped. Return a `Future` for automatic loading state |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Form submission record (form instance, success callback) |
| `onFailure` | `Function(dynamic)?` | `null` | Called when form validation fails |
| `showToastError` | `bool` | `true` | Show toast notification on form validation error |
| `width` | `double?` | `null` | Button width (defaults to full width) |

### Type-Specific Parameters

#### Button.outlined

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Theme outline color | Border stroke color |
| `textColor` | `Color?` | Theme primary color | Text color |

#### Button.textOnly

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Theme primary color | Text color |

#### Button.icon

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `icon` | `Widget` | required | The icon widget to display |
| `color` | `Color?` | Theme primary color | Background color |

#### Button.gradient

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Primary and tertiary colors | Gradient color stops |

#### Button.rounded

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Theme primary container color | Background color |
| `borderRadius` | `BorderRadius?` | Pill shape (height / 2) | Corner radius |

#### Button.transparency

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `color` | `Color?` | Theme-adaptive | Text color |

### ButtonAnimationStyle Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `duration` | `Duration` | Varies per type | Animation duration |
| `curve` | `Curve` | Varies per type | Animation curve |
| `enableHapticFeedback` | `bool` | Varies per type | Trigger haptic feedback on press |
| `translateY` | `double` | `4.0` | Clickable: vertical press distance |
| `shadowOffset` | `double` | `4.0` | Clickable: shadow depth |
| `scaleMin` | `double` | `0.92` | Bounce: minimum scale on press |
| `pulseScale` | `double` | `1.05` | Pulse: maximum scale during pulse |
| `squeezeX` | `double` | `0.95` | Squeeze: horizontal compression |
| `squeezeY` | `double` | `1.05` | Squeeze: vertical expansion |
| `jellyStrength` | `double` | `0.15` | Jelly: wobble intensity |
| `shineColor` | `Color` | `Colors.white` | Shine: highlight color |
| `shineWidth` | `double` | `0.3` | Shine: width of the shine band |
| `rippleScale` | `double` | `2.0` | Ripple: expansion scale |
| `morphRadius` | `double` | `24.0` | Morph: target border radius |
| `shakeOffset` | `double` | `8.0` | Shake: horizontal displacement |
| `shakeCount` | `int` | `3` | Shake: number of oscillations |

### ButtonSplashStyle Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Theme surface color | Splash effect color |
| `highlightColor` | `Color?` | Theme surface color | Highlight effect color |
| `splashOpacity` | `double` | `0.12` | Opacity of the splash |
| `highlightOpacity` | `double` | `0.06` | Opacity of the highlight |
| `borderRadius` | `BorderRadius?` | `null` | Splash clip radius |
