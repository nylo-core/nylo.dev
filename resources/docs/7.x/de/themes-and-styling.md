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
  - [Farbstile erweitern](#extending-color-styles "Farbstile erweitern")
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

Jedes Theme befindet sich in einem eigenen Unterverzeichnis unter `lib/resources/themes/`:

- Helles Theme – `lib/resources/themes/light/light_theme.dart`
- Helle Farben – `lib/resources/themes/light/light_theme_colors.dart`
- Dunkles Theme – `lib/resources/themes/dark/dark_theme.dart`
- Dunkle Farben – `lib/resources/themes/dark/dark_theme_colors.dart`

Beide Themes teilen sich einen gemeinsamen Builder unter `lib/resources/themes/base_theme.dart` und das `ColorStyles`-Interface unter `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Ein Theme erstellen

Wenn Sie mehrere Themes fuer Ihre App haben moechten, erstellen Sie die Theme-Dateien manuell unter `lib/resources/themes/`. Die folgenden Schritte verwenden `bright` als Beispiel — ersetzen Sie es durch Ihren Theme-Namen.

**Schritt 1:** Erstellen Sie die Theme-Datei unter `lib/resources/themes/bright/bright_theme.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**Schritt 2:** Erstellen Sie die Farbdatei unter `lib/resources/themes/bright/bright_theme_colors.dart`:

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

**Schritt 3:** Registrieren Sie das neue Theme in `lib/bootstrap/theme.dart`.

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

Sie koennen die Farben in `bright_theme_colors.dart` an Ihr Design anpassen.

<div id="theme-colors"></div>

## Theme-Farben

Um die Theme-Farben in Ihrem Projekt zu verwalten, schauen Sie sich die Verzeichnisse `lib/resources/themes/light/` und `lib/resources/themes/dark/` an. Jedes enthaelt die Farbdatei fuer sein Theme — `light_theme_colors.dart` und `dark_theme_colors.dart`.

Farbwerte sind in Gruppen (`general`, `appBar`, `bottomTabBar`) organisiert, die vom Framework definiert werden. Die Farbenklasse Ihres Themes erweitert `ColorStyles` und liefert eine Instanz jeder Gruppe:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Farben fuer die allgemeine Verwendung.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Farben fuer die App-Leiste.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Farben fuer die untere Tab-Leiste.
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

## Farben in Widgets verwenden

Verwenden Sie den Hilfsaufruf `nyColorStyle<T>(context)`, um die Farben des aktiven Themes abzurufen. Uebergeben Sie den `ColorStyles`-Typ Ihres Projekts, damit der Aufruf vollstaendig typisiert ist:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// innerhalb eines Widget-Builds:
final colors = nyColorStyle<ColorStyles>(context);

// die Hintergrundfarbe des aktiven Themes
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Farben aus einem bestimmten Theme lesen (unabhaengig davon, welches aktiv ist):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Basis-Stile

Basis-Stile ermoeglichen es Ihnen, jedes Theme ueber ein einziges Interface zu beschreiben. {{ config('app.name') }} wird mit `lib/resources/themes/color_styles.dart` geliefert, welches der Vertrag ist, den sowohl `light_theme_colors.dart` als auch `dark_theme_colors.dart` implementieren.

`ColorStyles` erweitert `ThemeColor` aus dem Framework, das drei vordefinierte Farbgruppen bereitstellt: `GeneralColors`, `AppBarColors` und `BottomTabBarColors`. Der Basis-Theme-Builder (`lib/resources/themes/base_theme.dart`) liest diese Gruppen beim Erstellen von `ThemeData`, sodass alles, was Sie darin einfuegen, automatisch mit den entsprechenden Widgets verbunden wird.

<br>

Datei `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Farben fuer die allgemeine Verwendung.
  @override
  GeneralColors get general;

  /// Farben fuer die App-Leiste.
  @override
  AppBarColors get appBar;

  /// Farben fuer die untere Tab-Leiste.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Die drei Gruppen stellen folgende Felder bereit:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Um Felder jenseits dieser Standardwerte hinzuzufuegen — eigene Buttons, Icons, Badges usw. — siehe [Farbstile erweitern](#extending-color-styles).

<div id="extending-color-styles"></div>

## Farbstile erweitern

<!-- uncertain: new section "Extending color styles" not present in previous locale file -->
Die drei Standard-Gruppen (`general`, `appBar`, `bottomTabBar`) sind ein Ausgangspunkt, keine feste Grenze. `lib/resources/themes/color_styles.dart` steht Ihnen zur Anpassung offen — fuegen Sie neue Farbgruppen (oder einzelne Felder) zu den Standardwerten hinzu und implementieren Sie diese dann in der Farbenklasse jedes Themes.

**1. Eine benutzerdefinierte Farbgruppe definieren**

Gruppieren Sie verwandte Farben in einer kleinen unveraenderlichen Klasse:

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

**2. Sie zu `ColorStyles` hinzufuegen**

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

  // Benutzerdefinierte Gruppen
  IconColors get icons;
}
```

**3. In der Farbenklasse jedes Themes implementieren**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...vorhandene Overrides...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Wiederholen Sie den gleichen `icons`-Override in `dark_theme_colors.dart` mit den Werten fuer den Dunkelmodus.

**4. In Ihren Widgets verwenden**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Theme wechseln

{{ config('app.name') }} unterstuetzt die Moeglichkeit, Themes zur Laufzeit zu wechseln.

Z.B. wenn Sie das Theme wechseln muessen, weil ein Benutzer auf einen Button tippt, um das "dunkle Theme" zu aktivieren.

Sie koennen dies wie folgt unterstuetzen:

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

## Schriftarten

Das Aktualisieren Ihrer primaeren Schriftart in der gesamten App ist in {{ config('app.name') }} einfach. Oeffnen Sie `lib/config/design.dart` und aktualisieren Sie `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Wir binden die <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a>-Bibliothek im Repository ein, sodass Sie alle Schriftarten mit wenig Aufwand verwenden koennen. Um zu einer anderen Google-Schriftart zu wechseln, aendern Sie einfach den Aufruf:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Schauen Sie sich die Schriftarten in der offiziellen <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a>-Bibliothek an, um mehr zu erfahren.

Muessen Sie eine benutzerdefinierte Schriftart verwenden? Schauen Sie sich diese Anleitung an - https://flutter.dev/docs/cookbook/design/fonts

Sobald Sie Ihre Schriftart hinzugefuegt haben, aendern Sie die Variable wie im folgenden Beispiel.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Design

Die Datei **lib/config/design.dart** wird zur Verwaltung der Design-Elemente Ihrer App verwendet. Alles wird ueber die Klasse `DesignConfig` bereitgestellt:

`DesignConfig.appFont` enthaelt die Schriftart fuer Ihre App.

`DesignConfig.logo` wird zur Anzeige des Logos Ihrer App verwendet.

Sie koennen **lib/resources/widgets/logo_widget.dart** aendern, um die Darstellung Ihres Logos anzupassen.

`DesignConfig.loader` wird zur Anzeige eines Laders verwendet. {{ config('app.name') }} verwendet diese Variable in einigen Hilfsmethoden als Standard-Loader-Widget.

Sie koennen **lib/resources/widgets/loader_widget.dart** aendern, um die Darstellung Ihres Laders anzupassen.

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
