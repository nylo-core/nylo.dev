# Themes & Styling

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Themes
  - [Helles & Dunkles Theme](#light-and-dark-themes "Helles & Dunkles Theme")
  - [Ein Theme erstellen](#creating-a-theme "Ein Theme erstellen")
- Konfiguration
  - [Theme-Farben](#theme-colors "Theme-Farben")
  - [Farben verwenden](#using-colors "Farben verwenden")
  - [Basis-Stile](#base-styles "Basis-Stile")
  - [Theme wechseln](#switching-theme "Theme wechseln")
  - [Schriftarten](#fonts "Schriftarten")
  - [Design](#design "Design")
- [Text-Extensions](#text-extensions "Text-Extensions")


<div id="introduction"></div>

## Einleitung

Sie koennen die UI-Stile Ihrer Anwendung mithilfe von Themes verwalten. Themes ermoeglichen es uns, z.B. die Schriftgroesse von Texten, das Erscheinungsbild von Buttons und das allgemeine Aussehen unserer Anwendung zu aendern.

Wenn Sie neu bei Themes sind, helfen Ihnen die Beispiele auf der Flutter-Website beim Einstieg <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">hier</a>.

Standardmaessig enthaelt {{ config('app.name') }} vorkonfigurierte Themes fuer `Light mode` und `Dark mode`.

Das Theme wird auch aktualisiert, wenn das Geraet in den <b>'light/dark'</b>-Modus wechselt.

<div id="light-and-dark-themes"></div>

## Helles & Dunkles Theme

- Helles Theme - `lib/resources/themes/light_theme.dart`
- Dunkles Theme - `lib/resources/themes/dark_theme.dart`

In diesen Dateien finden Sie die vordefinierte ThemeData und ThemeStyle.



<div id="creating-a-theme"></div>

## Ein Theme erstellen

Wenn Sie mehrere Themes fuer Ihre App haben moechten, bieten wir Ihnen einen einfachen Weg dafuer. Wenn Sie neu bei Themes sind, folgen Sie der Anleitung.

Fuehren Sie zuerst den folgenden Befehl im Terminal aus

``` bash
metro make:theme bright_theme
```

<b>Hinweis:</b> Ersetzen Sie **bright_theme** durch den Namen Ihres neuen Themes.

Dies erstellt ein neues Theme in Ihrem Verzeichnis `/resources/themes/` sowie eine Theme-Farben-Datei in `/resources/themes/styles/`.

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

Sie koennen die Farben fuer Ihr neues Theme in der Datei **/resources/themes/styles/bright_theme_colors.dart** aendern.

<div id="theme-colors"></div>

## Theme-Farben

Um die Theme-Farben in Ihrem Projekt zu verwalten, schauen Sie sich das Verzeichnis `lib/resources/themes/styles` an.
Dieses Verzeichnis enthaelt die Stilfarben fuer light_theme_colors.dart und dark_theme_colors.dart.

In dieser Datei sollten Sie etwas Aehnliches wie das Folgende haben.

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

<div id="using-colors"></div>

## Farben in Widgets verwenden

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

<div id="base-styles"></div>

## Basis-Stile

Basis-Stile ermoeglichen es Ihnen, verschiedene Widget-Farben von einer Stelle in Ihrem Code aus anzupassen.

{{ config('app.name') }} wird mit vorkonfigurierten Basis-Stilen fuer Ihr Projekt in `lib/resources/themes/styles/color_styles.dart` geliefert.

Diese Stile bieten ein Interface fuer Ihre Theme-Farben in `light_theme_colors.dart` und `dart_theme_colors.dart`.

<br>

Datei `lib/resources/themes/styles/color_styles.dart`

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

Sie koennen hier zusaetzliche Stile hinzufuegen und dann die Farben in Ihrem Theme implementieren.

<div id="switching-theme"></div>

## Theme wechseln

{{ config('app.name') }} unterstuetzt die Moeglichkeit, Themes zur Laufzeit zu wechseln.

Z.B. wenn Sie das Theme wechseln muessen, weil ein Benutzer auf einen Button tippt, um das "dunkle Theme" zu aktivieren.

Sie koennen dies wie folgt unterstuetzen:

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


<div id="fonts"></div>

## Schriftarten

Das Aktualisieren Ihrer primaeren Schriftart in der gesamten App ist in {{ config('app.name') }} einfach. Oeffnen Sie die Datei `lib/config/design.dart` und aktualisieren Sie das Folgende.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Wir binden die <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a>-Bibliothek im Repository ein, sodass Sie alle Schriftarten mit wenig Aufwand verwenden koennen.
Um die Schriftart zu aendern, koennen Sie Folgendes tun:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Schauen Sie sich die Schriftarten in der offiziellen <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a>-Bibliothek an, um mehr zu erfahren.

Muessen Sie eine benutzerdefinierte Schriftart verwenden? Schauen Sie sich diese Anleitung an - https://flutter.dev/docs/cookbook/design/fonts

Sobald Sie Ihre Schriftart hinzugefuegt haben, aendern Sie die Variable wie im folgenden Beispiel.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Design

Die Datei **config/design.dart** wird zur Verwaltung der Design-Elemente Ihrer App verwendet.

Die Variable `appFont` enthaelt die Schriftart fuer Ihre App.

Die Variable `logo` wird zur Anzeige des Logos Ihrer App verwendet.

Sie koennen **resources/widgets/logo_widget.dart** aendern, um die Darstellung Ihres Logos anzupassen.

Die Variable `loader` wird zur Anzeige eines Laders verwendet. {{ config('app.name') }} verwendet diese Variable in einigen Hilfsmethoden als Standard-Loader-Widget.

Sie koennen **resources/widgets/loader_widget.dart** aendern, um die Darstellung Ihres Laders anzupassen.

<div id="text-extensions"></div>

## Text-Extensions

Hier sind die verfuegbaren Text-Extensions, die Sie in {{ config('app.name') }} verwenden koennen.

| Regelname   | Verwendung | Info |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Wendet das **displayLarge** textTheme an |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Wendet das **displayMedium** textTheme an |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Wendet das **displaySmall** textTheme an |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Wendet das **headingLarge** textTheme an |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Wendet das **headingMedium** textTheme an |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Wendet das **headingSmall** textTheme an |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Wendet das **titleLarge** textTheme an |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Wendet das **titleMedium** textTheme an |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Wendet das **titleSmall** textTheme an |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Wendet das **bodyLarge** textTheme an |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Wendet das **bodyMedium** textTheme an |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Wendet das **bodySmall** textTheme an |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Wendet das **labelLarge** textTheme an |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Wendet das **labelMedium** textTheme an |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Wendet das **labelSmall** textTheme an |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Wendet fette Schriftstärke auf ein Text-Widget an |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Wendet leichte Schriftstärke auf ein Text-Widget an |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Setzt eine andere Textfarbe auf dem Text-Widget |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Richtet die Schrift links aus |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Richtet die Schrift rechts aus |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Richtet die Schrift zentriert aus |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Setzt die maximale Zeilenanzahl fuer das Text-Widget |

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
