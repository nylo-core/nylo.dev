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
  - [Fonts](#fonts "Fonts")
  - [Design](#design "Design")
- [Text Extensions](#text-extensions "Text extensions")


<a name="introduction"></a>
<br>
## Introduction

You can manage your application's UI styles using themes. Themes allow us to change i.e. the font size of text, how buttons appear and the general appearance of our application.

If you are new to themes, the examples on the Flutter website will help you get started <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">here</a>.

Out of the box, {{ config('app.name') }} includes pre-configured themes for `Light mode` and `Dark mode`.

The theme will also update if the device enters <b>'light/dark'</b> mode.

<a name="light-and-dark-themes"></a>
<br>

## Light & Dark themes

- Light theme - `lib/resources/themes/light_theme.dart`
- Dark theme - `lib/resources/themes/dark_theme.dart`

Inside these files, you'll find the ThemeData and ThemeStyle pre-defined.



<a name="creating-a-theme"></a>
<br>

## Creating a theme

If you want to have multiple themes for your app, we have an easy way for you to do this. If you're new to themes, follow along.

First, run the below command from the terminal 

``` bash
dart run nylo_framework:main make:theme bright_theme
# or with metro alias
metro make:theme bright_theme
```

<b>Note:</b> replace **bright_theme** with the name of your new theme.

This creates a new theme in your `/resources/themes/` directory and also a theme colors file in `/resources/themes/styles/`.

``` dart
// App Themes
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light theme",
    theme: lightTheme,
    colors: LightThemeColors(),
  ),
  BaseThemeConfig<ColorStyles>(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark theme",
    theme: darkTheme,
    colors: DarkThemeColors(),
  ),

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

You can modify the colors for your new theme in the **/resources/themes/styles/bright_theme_colors.dart** file.

<a name="theme-colors"></a>
<br>

## Theme Colors

To manage the theme colors in your project, check out the `lib/resources/themes/styles` directory.
This directory contains the style colors for the light_theme_colors.dart and dark_theme_colors.dart.

In this file, you should have something similar to the below.

``` dart
// e.g Light Theme colors
class LightThemeColors implements ColorStyles {
  // general
  @override
  Color get background => const Color(0xFFFFFFFF);

  @override
  Color get content => const Color(0xFF000000);
  @override
  Color get primaryAccent => const Color(0xFF0045a0);

  @override
  Color get surfaceBackground => Colors.white;
  @override
  Color get surfaceContent => Colors.black;

  // app bar
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // buttons
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // bottom tab bar
  @override
  Color get bottomTabBarBackground => Colors.white;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // toast notification
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<a name="using-colors"></a>
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
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// or 

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Light theme colors - primary content
  ),
),
```

<a name="base-styles"></a>
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
  @override
  Color get background;
  @override
  Color get content;
  @override
  Color get primaryAccent;

  @override
  Color get surfaceBackground;
  @override
  Color get surfaceContent;

  // app bar
  @override
  Color get appBarBackground;
  @override
  Color get appBarPrimaryContent;

  @override
  Color get buttonBackground;
  @override
  Color get buttonContent;

  @override
  Color get buttonSecondaryBackground;
  @override
  Color get buttonSecondaryContent;

  // bottom tab bar
  @override
  Color get bottomTabBarBackground;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // toast notification
  Color get toastNotificationBackground;
}
```

You can add additional styles here and then implement the colors in your theme.

<a name="switching-theme"></a>
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


<a name="fonts"></a>
<br>

## Fonts

Updating your primary font throughout the app is easy in {{ config('app.name') }}. Open the `lib/config/design.dart` file and update the below.

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

<a name="design"></a>
<br>

## Design

The **config/design.dart** file is used for managing the design elements for your app.

`appFont` variable contains the font for your app.

`logo` variable is used to display your app's Logo. 

You can modify **resources/widgets/logo_widget.dart** to customize how you want to display your Logo.

`loader` variable is used to display a loader. {{ config('app.name') }} will use this variable in some helper methods as the default loader widget.

You can modify **resources/widgets/loader_widget.dart** to customize how you want to display your Loader.

<a name="text-extensions"></a>
<br>

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

---

<a name="text-extension-display-large"></a>
<br>

#### Display large

``` dart
Text("Hello World").displayLarge()
```

<a name="text-extension-display-medium"></a>
<br>

#### Display medium

``` dart
Text("Hello World").displayMedium()
```

<a name="text-extension-display-small"></a>
<br>

#### Display small

``` dart
Text("Hello World").displaySmall()
```

<a name="text-extension-heading-large"></a>
<br>

#### Heading large

``` dart
Text("Hello World").headingLarge()
```

<a name="text-extension-heading-medium"></a>
<br>

#### Heading medium

``` dart
Text("Hello World").headingMedium()
```

<a name="text-extension-heading-small"></a>
<br>

#### Heading small

``` dart
Text("Hello World").headingSmall()
```

<a name="text-extension-title-large"></a>
<br>

#### Title large

``` dart
Text("Hello World").titleLarge()
```

<a name="text-extension-title-medium"></a>
<br>

#### Title medium

``` dart
Text("Hello World").titleMedium()
```

<a name="text-extension-title-small"></a>
<br>

#### Title small

``` dart
Text("Hello World").titleSmall()
```

<a name="text-extension-body-large"></a>
<br>

#### Body large

``` dart
Text("Hello World").bodyLarge()
```

<a name="text-extension-body-medium"></a>
<br>

#### Body medium

``` dart
Text("Hello World").bodyMedium()
```

<a name="text-extension-body-small"></a>
<br>

#### Body small

``` dart
Text("Hello World").bodySmall()
```

<a name="text-extension-label-large"></a>
<br>

#### Label large

``` dart
Text("Hello World").labelLarge()
```

<a name="text-extension-label-medium"></a>
<br>

#### Label medium

``` dart
Text("Hello World").labelMedium()
```

<a name="text-extension-label-small"></a>
<br>

#### Label small

``` dart
Text("Hello World").labelSmall()
```

<a name="text-extension-font-weight-bold"></a>
<br>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<a name="text-extension-font-weight-light"></a>
<br>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<a name="text-extension-set-color"></a>
<br>

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Color from your colorStyles
```

<a name="text-extension-align-left"></a>
<br>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<a name="text-extension-align-right"></a>
<br>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<a name="text-extension-align-center"></a>
<br>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<a name="text-extension-set-max-lines"></a>
<br>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
