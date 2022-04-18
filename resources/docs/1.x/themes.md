# Themes

---

- <span class="text-grey">Sponsors</span>
- [Become a sponsor](https://nylo.dev/contributions)

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to themes")
- Themes
  - [Light & Dark themes](#light-and-dark-themes "Light and dark themes")
- Configuration
  - [Theme colors](#theme-colors "Theme colours")
  - [Fonts](#fonts "Fonts")


<a name="introduction"></a>
<br>
## Introduction

Nylo includes themes for `Light mode` and `Dark mode`.

Both themes come pre-configured and ready for use. The theme will also update automatically change if the device enters 'light/dark mode', on IOS you can manually set a device to 'light/dark mode' by going into settings.

<a name="light-and-dark-themes"></a>
<br>

## Light & Dark themes

- The light theme is named `default_theme.dart`
- The dark theme is named `dark_theme.dart`

Inside these files, you'll find the ThemeData and ThemeStyle pre-defined.
Changes to the font and app colors can be managed in the next section.

<a name="theme-colors"></a>
<br>

## Theme Colors

To manage the theme colors in your project check out the `app_theme.dart` file.
This contains the base configuration variables used in both themes.

In this file, you should have something similar to the below.

``` dart
// Theme colors
class AppColors {
  // MAIN
  Color _mainLightColor = Color(0xFF232c33);
  Color _mainDarkColor = Color(0xFFFAFAFA);

  // SECONDARY
  Color _secondLightColor = Color(0xFF232c33);
  Color _secondDarkColor = Color(0xFFccccdd);

  // ACCENT
  Color _accentLightColor = Color(0xFF87c694);
  Color _accentDarkColor = Color(0xFF9999aa);

  // SCAFFOLD
  Color _scaffoldDarkColor = Color(0xFF2C2C2C);
  Color _scaffoldLightColor = Color(0xFFFAFAFA);
}
```

The `LightColor` variables will update the light theme and the `DarkColor` variables update the dark theme.

Add more colors here too as you'll be able to call the `AppTheme` class to get colors that you might need in other areas of your app.

<a name="fonts"></a>
<br>

## Fonts

Updating your primary font throughout the app is very easy in Nylo, open the `app_theme.dart` file and update the below.

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