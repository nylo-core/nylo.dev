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
  - [Switching theme](#switching-theme "Switching theme")
  - [Defining themes](#defining-themes "Defining themes")
  - [Fonts](#fonts "Fonts")
  - [Design](#design "Design")
- [Text Extensions](#text-extensions "Text extensions")


<div id="introduction"></div>
<br>
## Introduction

You can manage your application's UI styles using themes. Themes allow us to change i.e. the font size of text, how buttons appear and the general appearance of our application.

If you are new to themes, the examples on the Flutter website will help you get started <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">here</a>.

Out of the box, {{ config('app.name') }} includes pre-configured themes for `Light mode` and `Dark mode`.

The theme will also update if the device enters <b>'light/dark'</b> mode.

<div id="light-and-dark-themes"></div>
<br>

## Light & Dark themes

- Light theme - `lib/resources/themes/light_theme.dart`
- Dark theme - `lib/resources/themes/dark_theme.dart`

Inside these files, you'll find the ThemeData and ThemeStyle pre-defined.



<div id="creating-a-theme"></div>
<br>

## Creating a theme

If you want to have multiple themes for your app, we have an easy way for you to do this. If you're new to themes, follow along.

First, run the below command from the terminal 

```bash
dart run nylo_framework:main make:theme bright_theme
```
<b>Note:</b> replace **bright_theme** with the name of your new theme.

This creates a new theme in your `/resources/themes/` directory and also a theme colors file in `/resources/themes/styles/`.

You can modify the colors for your new theme in the **/resources/themes/styles/bright_theme_colors.dart** file.

When you are ready to use the new theme, go to `config/theme.dart`.

**Next**, add the colors below the **Theme Colors** section, like the below example.

``` dart
// Light Colors
ColorStyles lightColors = LightThemeColors();

// Dark Colors
ColorStyles darkColors = DarkThemeColors();

// My New Bright Colors 
ColorStyles brightColors = BrightThemeColors();
...
```

**Next**, add your new theme in the `ThemeConfig` class like the below example.
``` dart
...
// Preset Themes
class ThemeConfig {

  // LIGHT
  static BaseThemeConfig<ColorStyles> light() => BaseThemeConfig<ColorStyles>(
    id: "default_light_theme",
    description: "Light theme",
    theme: lightTheme(lightColors),
    colors: lightColors,
  );

  // My new theme
  static BaseThemeConfig<ColorStyles> bright() => BaseThemeConfig<ColorStyles>(
   id: "default_bright_theme", // id when switching theme in the app
   description: "Bright theme",
   theme: brightTheme(brightColors),
   colors: brightColors,
  );
```

Lastly, add the new theme in the `appThemes` config. Here's an example.
``` dart
final appThemes = [

  ThemeConfig.bright(), // If first, it will be the default theme

  ThemeConfig.light(),

  ThemeConfig.dark(),
];
```

That's it. Try running the app to see the changes.


<div id="theme-colors"></div>
<br>

## Theme Colors

To manage the theme colors in your project, check out the `lib/resources/themes/styles` directory.
This directory contains the style colors for the light_theme_colors.dart and dark_theme_colors.dart.

In this file, you should have something similar to the below.

``` dart
// e.g Light Theme colors
class LightThemeColors implements ColorStyles {
  // general
  Color get background => const Color(0xFFFFFFFF);
  Color get primaryContent => const Color(0xFF000000);
  Color get primaryAccent => const Color(0xFF87c694);

  Color get surfaceBackground => Colors.white;
  Color get surfaceContent => Colors.black;

  // app bar
  Color get appBarBackground => Colors.blue;
  Color get appBarPrimaryContent => Colors.white;

  // buttons
  Color get buttonBackground => Colors.blueAccent;
  Color get buttonPrimaryContent => Colors.white;

  // bottom tab bar
  Color get bottomTabBarBackground => Colors.white;

  // bottom tab bar - icons
  Color get bottomTabBarIconSelected => Colors.blue;
  Color get bottomTabBarIconUnselected => Colors.black54;

  // bottom tab bar - label
  Color get bottomTabBarLabelUnselected => Colors.black45;
  Color get bottomTabBarLabelSelected => Colors.black;
}
```

<div id="using-colors"></div>
<br>

## Using colors in widgets

``` dart
import 'package:flutter_app/config/theme.dart';
...

// gets the light/dark background colour depending on the theme
ThemeColor.get(context).background

// e.g. of using the "ThemeColor" class
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).primaryContent // Color - primary content
  ),
),

// or 

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.primaryContent // Light theme colors - primary content
  ),
),
```

<div id="base-styles"></div>
<br>

## Base styles

Base styles allow you to customize various widget colors from one area in your code.

{{ config('app.name') }} ships with pre-configured base styles for your project located `lib/resources/themes/styles/color_styles.dart`.

These styles provide an interface for your theme colors in `light_theme_colors.dart` and `dart_theme_colors.dart`.

<br>

File `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {
  // general
  Color get background;
  Color get primaryContent;
  Color get primaryAccent;

  // app bar
  Color get appBarBackground;
  Color get appBarPrimaryContent;

  // buttons
  Color get buttonBackground;
  Color get buttonPrimaryContent;

  // bottom tab bar
  Color get bottomTabBarBackground;

  // bottom tab bar - icons
  Color get bottomTabBarIconSelected;
  Color get bottomTabBarIconUnselected;

  // bottom tab bar - label
  Color get bottomTabBarLabelUnselected;
  Color get bottomTabBarLabelSelected;
}
```

You can add additional styles here and then implement the colors in your theme.

<div id="switching-theme"></div>
<br>

## Switching theme

{{ config('app.name') }} supports the ability to switch themes on the fly. 

E.g. If you need to switch the theme if a user taps a button to activate the "dark theme".

You can support that by doing the below:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
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


<div id="defining-themes"></div>
<br>

## Defining Themes

Themes are defined inside your `config/app_theme.dart` file. 

``` dart
...
// App Themes
final appThemes = [

  ThemeConfig.light(),

  ThemeConfig.dark(),
];
...
```

<div id="fonts"></div>
<br>

## Fonts

Updating your primary font throughout the app is easy in {{ config('app.name') }}. Open the `lib/config/font.dart` file and update the below.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

We include the <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> library in the repository, so you can start using all the fonts with little effort.
To update the font to something else, you can do the following:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Check out the fonts on the official <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> library to understand more

Need to use a custom font? Check out this guide - https://flutter.dev/docs/cookbook/design/fonts

Once you've added your font, change the variable like the below example.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>
<br>

## Design

The **config/design.dart** file is used for managing the design elements for your app.

`logo` variable is used to display your app's Logo. 

You can modify **resources/widgets/logo_widget.dart** to customize how you want to display your Logo.

`loader` variable is used to display a loader. {{ config('app.name') }} will use this variable in some helper methods as the default loader widget.

You can modify **resources/widgets/loader_widget.dart** to customize how you want to display your Loader.

<div id="text-extensions"></div>
<br>

## Text Extensions

Here are the available text extensions that you can use in {{ config('app.name') }}.

| Rule Name   | Usage | Info |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge(context)  | Applies the **displayLarge** textTheme |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium(context)  | Applies the **displayMedium** textTheme |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall(context)  | Applies the **displaySmall** textTheme |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge(context)  | Applies the **headingLarge** textTheme |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium(context)  | Applies the **headingMedium** textTheme |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall(context)  | Applies the **headingSmall** textTheme |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge(context)  | Applies the **titleLarge** textTheme |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium(context)  | Applies the **titleMedium** textTheme |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall(context)  | Applies the **titleSmall** textTheme |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge(context)  | Applies the **bodyLarge** textTheme |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium(context)  | Applies the **bodyMedium** textTheme |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall(context)  | Applies the **bodySmall** textTheme |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Applies font weight bold to a Text widget |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Applies font weight light to a Text widget |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Set a different text color on the Text widget |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Align the font to the left |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Align the font to the right |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Align the font to the center |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Set the maximum lines for the text widget |

<br>

---

<div id="text-extension-display-large"></div>
<br>

#### Display large

``` dart
Text("Hello World").displayLarge(context)
```

<div id="text-extension-display-medium"></div>
<br>

#### Display medium

``` dart
Text("Hello World").displayMedium(context)
```

<div id="text-extension-display-small"></div>
<br>

#### Display small

``` dart
Text("Hello World").displaySmall(context)
```

<div id="text-extension-heading-large"></div>
<br>

#### Heading large

``` dart
Text("Hello World").headingLarge(context)
```

<div id="text-extension-heading-medium"></div>
<br>

#### Heading medium

``` dart
Text("Hello World").headingMedium(context)
```

<div id="text-extension-heading-small"></div>
<br>

#### Heading small

``` dart
Text("Hello World").headingSmall(context)
```

<div id="text-extension-title-large"></div>
<br>

#### Title large

``` dart
Text("Hello World").titleLarge(context)
```

<div id="text-extension-title-medium"></div>
<br>

#### Title medium

``` dart
Text("Hello World").titleMedium(context)
```

<div id="text-extension-title-small"></div>
<br>

#### Title small

``` dart
Text("Hello World").titleSmall(context)
```

<div id="text-extension-body-large"></div>
<br>

#### Body large

``` dart
Text("Hello World").bodyLarge(context)
```

<div id="text-extension-body-medium"></div>
<br>

#### Body medium

``` dart
Text("Hello World").bodyMedium(context)
```

<div id="text-extension-body-small"></div>
<br>

#### Body small

``` dart
Text("Hello World").bodySmall(context)
```

<div id="text-extension-font-weight-bold"></div>
<br>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>
<br>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>
<br>

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.primaryAccent)
// Color from your colorStyles
```

<div id="text-extension-align-left"></div>
<br>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>
<br>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>
<br>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>
<br>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
