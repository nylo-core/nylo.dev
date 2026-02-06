# Themes

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to themes")
- Themes
  - [Light & Dark themes](#light-and-dark-themes "Light and dark themes")
  - [Creating themes](#creating-a-new-theme "Creating themes")
- Configuration
  - [Theme colors](#theme-colors "Theme colours")
  - [Using colors](#using-colors "Using colours")
  - [Base styles](#base-styles "Base styles")
  - [Switching theme](#switching-theme "Switching theme")
  - [Defining themes](#defining-themes "Defining themes")
  - [Fonts](#fonts "Fonts")


<div id="introduction"></div>
## Introduction

Nylo includes themes for `Light mode` and `Dark mode`.

Both themes come pre-configured and ready for use. The theme will also update automatically change if the device enters 'light/dark mode', on IOS you can manually set a device to 'light/dark mode' by going into settings.

<div id="light-and-dark-themes"></div>

## Light & Dark themes

- The light theme is named `lib/resources/themes/light_theme.dart`
- The dark theme is named `lib/resources/themes/dark_theme.dart`

Inside these files, you'll find the ThemeData and ThemeStyle pre-defined.
Changes to the font and app colors can be managed in the next section.



<div id="creating-a-new-theme"></div>

## Creating a new theme

If you want to have multiple themes for your app, we have an easy way for you to do this. If you're new to themes, follow along.

First, run the following command from the terminal `flutter pub run nylo_framework:main make:theme bright_theme`, replace **bright_theme** with the name of your new theme.

This will create a new theme in your `/resources/themes/` directory and also a theme colors file in `/resources/themes/styles/`.

You can modify the colors for your new theme in **/resources/themes/styles/bright_theme_colors.dart** file (bright_theme_colors.dart as the example).

When you are ready to use the new theme, go to `config/app_theme.dart`.

**Next**, add the colors below the **Theme Colors** section like the below example.

``` dart
// Light Colors
BaseColorStyles lightColors = LightThemeColors();

// Dark Colors
BaseColorStyles darkColors = DarkThemeColors();

// My New Bright Colors 
BaseColorStyles brightColors = BrightThemeColors();
...
```

**Next**, add your new theme in the `ThemeConfig` class like the below example.
``` dart
...
// Preset Themes
class ThemeConfig {

  // LIGHT
  static BaseThemeConfig light() => BaseThemeConfig(
    id: "default_light_theme",
    description: "Light theme",
    theme: lightTheme(lightColors),
    colors: lightColors,
  );

  // My new theme
  static BaseThemeConfig bright() => BaseThemeConfig(
   id: "default_bright_theme", // id when switching theme in the app
   description: "Bright theme",
   theme: brightTheme(brightColors),
   colors: brightColors,
  );
```

Lastly, add the new theme in the `appThemes` config. Here's an example.
``` dart
final appThemes = [

  ThemeConfig.bright(), // If first in the array, it will be the default theme

  ThemeConfig.light(),

  ThemeConfig.dark(),
];
```

That's it. Now, trying running the app and see the changes.


<div id="theme-colors"></div>

## Theme Colors

To manage the theme colors in your project check out the `lib/resources/themes/styles` directory.
This directory contains the style colors for the light_theme_colors.dart and dark_theme_colors.dart.

In this file, you should have something similar to the below.

``` dart
// e.g Light Theme colors
class LightThemeColors implements BaseStyles {
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

## Using colors in widgets

``` dart
import 'package:flutter_app/config/app_theme.dart';
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

## Base styles

Base styles are an easy way to customize various widget colours from one area in your code. 

Nylo ships with pre-configured base styles for your project located `lib/resources/themes/styles/base_styles.dart`.

These styles provide an interface for your theme colors in `light_theme_colors.dart` and `dart_theme_colors.dart`.

<br>

File `lib/resources/themes/styles/base_styles.dart`

``` dart
abstract class BaseStyles {
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

You can add additional styles here and then implement the colours in your theme.

<div id="switching-theme"></div>

## Switching theme

Nylo supports the ablity to switch themes on the fly. 

E.g. If you need to switch theme if a user taps a button to activate the "dark theme".

You can support that by doing the below:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
...

TextButton(onPressed: () {

    // set theme to use the "dark theme" 
    NyTheme.set(context, id: "default_dark_theme");
    setState(() { });
  
  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // set theme to use the "light theme" 
    NyTheme.set(context, id: "default_light_theme");
    setState(() { });
  
  }, child: Text("Light Theme")
),
```


<div id="defining-themes"></div>

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

## Fonts

Updating your primary font throughout the app is very easy in Nylo, open the `lib/config/app_font.dart` file and update the below.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

We include the [GoogleFonts](https://pub.dev/packages/google_fonts) library in the repository so you can start using all the fonts with little effort.
To update the font to something else you can do the following:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Check out the fonts on the official [GoogleFonts](https://pub.dev/packages/google_fonts) library to understand more

If you want to use custom font, first add your new font into the app. Check out this guide if you're unsure - https://flutter.dev/docs/cookbook/design/fonts

Once you've added your font, change the variable like the below example.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```