# Themes & Styling

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to themes")
- Themes
  - [Light & Dark themes](#light-and-dark-themes "Light and dark themes")
  - [Creating a theme](#creating-a-theme "Creating a theme")
- Configuration
  - [Theme colors](#theme-colors "Theme colours")
  - [Using colors](#using-colors "Using colours")
  - [Base styles](#base-styles "Base styles")
  - [Extending color styles](#extending-color-styles "Extending color styles")
  - [Switching theme](#switching-theme "Switching theme")
  - [Fonts](#fonts "Fonts")
  - [Design](#design "Design")
- [Text Extensions](#text-extensions "Text extensions")


<div id="introduction"></div>

## Introduction

You can manage your application's UI styles using themes. Themes allow us to change i.e. the font size of text, how buttons appear and the general appearance of our application.

If you are new to themes, the examples on the Flutter website will help you get started <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">here</a>.

Out of the box, {{ config('app.name') }} includes pre-configured themes for `Light mode` and `Dark mode`.

The theme will also update if the device enters <b>'light/dark'</b> mode.

<div id="light-and-dark-themes"></div>

## Light & Dark themes

Each theme lives in its own subdirectory under `lib/resources/themes/`:

- Light theme – `lib/resources/themes/light/light_theme.dart`
- Light colors – `lib/resources/themes/light/light_theme_colors.dart`
- Dark theme – `lib/resources/themes/dark/dark_theme.dart`
- Dark colors – `lib/resources/themes/dark/dark_theme_colors.dart`

Both themes share a common builder at `lib/resources/themes/base_theme.dart` and the `ColorStyles` interface at `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Creating a theme

If you want to have multiple themes for your app, create the theme files manually under `lib/resources/themes/`. The steps below use `bright` as an example — replace it with your theme name.

**Step 1:** Create the theme file at `lib/resources/themes/bright/bright_theme.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**Step 2:** Create the colors file at `lib/resources/themes/bright/bright_theme_colors.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/color_styles.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BrightThemeColors extends ColorStyles {
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFDE7),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFFFBC02D),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  @override
  AppBarColors get appBar => const AppBarColors(
        background: Color(0xFFFBC02D),
        content: Colors.white,
      );

  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Color(0xFFFBC02D),
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

**Step 3:** Register the new theme in `lib/bootstrap/theme.dart`.

``` dart
// lib/bootstrap/theme.dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),

  BaseThemeConfig<ColorStyles>(
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

You can adjust the colors in `bright_theme_colors.dart` to match your design.

<div id="theme-colors"></div>

## Theme Colors

To manage the theme colors in your project, check out the `lib/resources/themes/light/` and `lib/resources/themes/dark/` directories. Each contains the colors file for its theme — `light_theme_colors.dart` and `dark_theme_colors.dart`.

Color values are organized into groups (`general`, `appBar`, `bottomTabBar`) defined by the framework. Your theme's colors class extends `ColorStyles` and supplies an instance of each group:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Colors for general use.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Colors for the app bar.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Colors for the bottom tab bar.
  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Colors.blue,
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

<div id="using-colors"></div>

## Using colors in widgets

Use the `nyColorStyle<T>(context)` helper to read the active theme's colors. Pass your project's `ColorStyles` type so the call is fully typed:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// inside a widget build:
final colors = nyColorStyle<ColorStyles>(context);

// the active theme's background colour
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Read colors from a specific theme (regardless of which one is active):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Base styles

Base styles let you describe every theme through a single interface. {{ config('app.name') }} ships with `lib/resources/themes/color_styles.dart`, which is the contract that `light_theme_colors.dart` and `dark_theme_colors.dart` both implement.

`ColorStyles` extends `ThemeColor` from the framework, which exposes three pre-defined color groups: `GeneralColors`, `AppBarColors`, and `BottomTabBarColors`. The base theme builder (`lib/resources/themes/base_theme.dart`) reads these groups when constructing `ThemeData`, so anything you put in them is wired into the matching widgets automatically.

<br>

File `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Colors for general use.
  @override
  GeneralColors get general;

  /// Colors for the app bar.
  @override
  AppBarColors get appBar;

  /// Colors for the bottom tab bar.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

The three groups expose the following fields:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

To add fields beyond these defaults — your own buttons, icons, badges, etc. — see [Extending color styles](#extending-color-styles).

<div id="extending-color-styles"></div>

## Extending color styles

The three default groups (`general`, `appBar`, `bottomTabBar`) are a starting point, not a hard limit. `lib/resources/themes/color_styles.dart` is yours to modify — add new color groups (or single fields) on top of the defaults, then implement them in each theme's colors class.

**1. Define a custom color group**

Group related colors into a small immutable class:

``` dart
import 'package:flutter/material.dart';

class IconColors {
  final Color iconBackground;
  final Color iconBackground1;

  const IconColors({
    required this.iconBackground,
    required this.iconBackground1,
  });
}
```

**2. Add it to `ColorStyles`**

``` dart
// lib/resources/themes/color_styles.dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  @override
  GeneralColors get general;
  @override
  AppBarColors get appBar;
  @override
  BottomTabBarColors get bottomTabBar;

  // Custom groups
  IconColors get icons;
}
```

**3. Implement it in each theme's colors class**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...existing overrides...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Repeat the same `icons` override in `dark_theme_colors.dart` with the dark-mode values.

**4. Use it in your widgets**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Switching theme

{{ config('app.name') }} supports the ability to switch themes on the fly. 

E.g. If you need to switch the theme if a user taps a button to activate the "dark theme".

You can support that by doing the below:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // set theme to use the "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // set theme to use the "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Fonts

Updating your primary font throughout the app is easy in {{ config('app.name') }}. Open `lib/config/design.dart` and update `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

We include the <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> library in the repository, so you can start using all the fonts with little effort. To switch to a different Google Font, just change the call:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Check out the fonts on the official <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> library to understand more.

Need to use a custom font? Check out this guide - https://flutter.dev/docs/cookbook/design/fonts

Once you've added your font, change the variable like the below example.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Design

The **lib/config/design.dart** file is used for managing the design elements for your app. Everything is exposed through the `DesignConfig` class:

`DesignConfig.appFont` contains the font for your app.

`DesignConfig.logo` is used to display your app's Logo.

You can modify **lib/resources/widgets/logo_widget.dart** to customize how you want to display your Logo.

`DesignConfig.loader` is used to display a loader. {{ config('app.name') }} will use this variable in some helper methods as the default loader widget.

You can modify **lib/resources/widgets/loader_widget.dart** to customize how you want to display your Loader.

<div id="text-extensions"></div>

## Text Extensions

Here are the available text extensions that you can use in {{ config('app.name') }}.

| Rule Name   | Usage | Info |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Applies the **displayLarge** textTheme |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Applies the **displayMedium** textTheme |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Applies the **displaySmall** textTheme |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Applies the **headingLarge** textTheme |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Applies the **headingMedium** textTheme |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Applies the **headingSmall** textTheme |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Applies the **titleLarge** textTheme |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Applies the **titleMedium** textTheme |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Applies the **titleSmall** textTheme |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Applies the **bodyLarge** textTheme |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Applies the **bodyMedium** textTheme |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Applies the **bodySmall** textTheme |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Applies the **labelLarge** textTheme |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Applies the **labelMedium** textTheme |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Applies the **labelSmall** textTheme |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Applies font weight bold to a Text widget |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Applies font weight light to a Text widget |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Set a different text color on the Text widget |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Align the font to the left |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Align the font to the right |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Align the font to the center |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Set the maximum lines for the text widget |

<br>


<div id="text-extension-display-large"></div>

#### Display large

``` dart
Text("Hello World").displayLarge()
```

<div id="text-extension-display-medium"></div>

#### Display medium

``` dart
Text("Hello World").displayMedium()
```

<div id="text-extension-display-small"></div>

#### Display small

``` dart
Text("Hello World").displaySmall()
```

<div id="text-extension-heading-large"></div>

#### Heading large

``` dart
Text("Hello World").headingLarge()
```

<div id="text-extension-heading-medium"></div>

#### Heading medium

``` dart
Text("Hello World").headingMedium()
```

<div id="text-extension-heading-small"></div>

#### Heading small

``` dart
Text("Hello World").headingSmall()
```

<div id="text-extension-title-large"></div>

#### Title large

``` dart
Text("Hello World").titleLarge()
```

<div id="text-extension-title-medium"></div>

#### Title medium

``` dart
Text("Hello World").titleMedium()
```

<div id="text-extension-title-small"></div>

#### Title small

``` dart
Text("Hello World").titleSmall()
```

<div id="text-extension-body-large"></div>

#### Body large

``` dart
Text("Hello World").bodyLarge()
```

<div id="text-extension-body-medium"></div>

#### Body medium

``` dart
Text("Hello World").bodyMedium()
```

<div id="text-extension-body-small"></div>

#### Body small

``` dart
Text("Hello World").bodySmall()
```

<div id="text-extension-label-large"></div>

#### Label large

``` dart
Text("Hello World").labelLarge()
```

<div id="text-extension-label-medium"></div>

#### Label medium

``` dart
Text("Hello World").labelMedium()
```

<div id="text-extension-label-small"></div>

#### Label small

``` dart
Text("Hello World").labelSmall()
```

<div id="text-extension-font-weight-bold"></div>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Color from your colorStyles
```

<div id="text-extension-align-left"></div>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
