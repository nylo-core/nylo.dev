# Themes & Styling

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do motywow")
- Motywy
  - [Motywy jasny i ciemny](#light-and-dark-themes "Motywy jasny i ciemny")
  - [Tworzenie motywu](#creating-a-theme "Tworzenie motywu")
- Konfiguracja
  - [Kolory motywu](#theme-colors "Kolory motywu")
  - [Uzycie kolorow](#using-colors "Uzycie kolorow")
  - [Style bazowe](#base-styles "Style bazowe")
  - [Rozszerzanie stylow kolorow](#extending-color-styles "Rozszerzanie stylow kolorow")
  - [Przelaczanie motywu](#switching-theme "Przelaczanie motywu")
  - [Czcionki](#fonts "Czcionki")
  - [Design](#design "Design")
- [Rozszerzenia tekstu](#text-extensions "Rozszerzenia tekstu")


<div id="introduction"></div>

## Wprowadzenie

Mozesz zarzadzac stylami interfejsu uzytkownika aplikacji za pomoca motywow. Motywy pozwalaja zmieniac np. rozmiar czcionki tekstu, wyglad przyciskow i ogolny wyglad aplikacji.

Jesli jestes nowy w temacie motywow, przyklady na stronie Flutter pomoga Ci zaczac <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">tutaj</a>.

{{ config('app.name') }} zawiera wstepnie skonfigurowane motywy dla `trybu jasnego` i `trybu ciemnego`.

Motyw bedzie sie rowniez aktualizowac, jesli urzadzenie wejdzie w tryb <b>'jasny/ciemny'</b>.

<div id="light-and-dark-themes"></div>

## Motywy jasny i ciemny

Kazdy motyw ma wlasny podkatalog w `lib/resources/themes/`:

- Motyw jasny – `lib/resources/themes/light/light_theme.dart`
- Kolory jasne – `lib/resources/themes/light/light_theme_colors.dart`
- Motyw ciemny – `lib/resources/themes/dark/dark_theme.dart`
- Kolory ciemne – `lib/resources/themes/dark/dark_theme_colors.dart`

Oba motywy wspoldziela wspolny kreator w `lib/resources/themes/base_theme.dart` oraz interfejs `ColorStyles` w `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Tworzenie motywu

Jesli chcesz miec wiele motywow w swojej aplikacji, mamy latwy sposob, aby to zrobic. Jesli jestes nowy w temacie motywow, postepuj zgodnie z instrukcjami.

Najpierw uruchom ponizsze polecenie z terminala

``` bash
dart run nylo_framework:main make:theme bright_theme
```

<b>Uwaga:</b> zastap **bright_theme** nazwa swojego nowego motywu.

Tworzy to nowy katalog motywu w `lib/resources/themes/bright/` zawierajacy zarowno `bright_theme.dart`, jak i `bright_theme_colors.dart`, a nastepnie rejestruje go w `lib/bootstrap/theme.dart`.

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

  BaseThemeConfig<ColorStyles>( // nowy motyw dodany automatycznie
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

Mozesz zmodyfikowac kolory nowego motywu w pliku **lib/resources/themes/bright/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Kolory motywu

Aby zarzadzac kolorami motywu w projekcie, sprawdz katalogi `lib/resources/themes/light/` i `lib/resources/themes/dark/`. Kazdy zawiera plik kolorow dla swojego motywu — `light_theme_colors.dart` i `dark_theme_colors.dart`.

Wartosci kolorow sa zorganizowane w grupy (`general`, `appBar`, `bottomTabBar`) zdefiniowane przez framework. Klasa kolorow motywu rozszerza `ColorStyles` i dostarcza instancje kazdej grupy:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Kolory do ogolnego uzytku.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Kolory paska aplikacji.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Kolory dolnego paska zakladek.
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

## Uzycie kolorow w widgetach

Uzyj pomocnika `nyColorStyle<T>(context)`, aby odczytac kolory aktywnego motywu. Podaj typ `ColorStyles` swojego projektu, aby wywolanie bylo w pelni typowane:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// wewnatrz metody build widgetu:
final colors = nyColorStyle<ColorStyles>(context);

// kolor tla aktywnego motywu
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Odczyt kolorow z konkretnego motywu (niezaleznie od aktywnego):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Style bazowe

Style bazowe pozwalaja opisac kazdy motyw przez jeden interfejs. {{ config('app.name') }} dostarcza `lib/resources/themes/color_styles.dart`, ktory jest kontraktem implementowanym przez zarowno `light_theme_colors.dart`, jak i `dark_theme_colors.dart`.

`ColorStyles` rozszerza `ThemeColor` z frameworka, ktory udostepnia trzy wstepnie zdefiniowane grupy kolorow: `GeneralColors`, `AppBarColors` i `BottomTabBarColors`. Kreator bazowego motywu (`lib/resources/themes/base_theme.dart`) odczytuje te grupy podczas budowania `ThemeData`, wiec wszystko, co w nich umieszcisz, zostanie automatycznie podlaczone do odpowiednich widgetow.

<br>

Plik `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Kolory do ogolnego uzytku.
  @override
  GeneralColors get general;

  /// Kolory paska aplikacji.
  @override
  AppBarColors get appBar;

  /// Kolory dolnego paska zakladek.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Trzy grupy udostepniaja nastepujace pola:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Aby dodac pola wykraczajace poza te domyslne — wlasne przyciski, ikony, znaczniki itp. — zob. [Rozszerzanie stylow kolorow](#extending-color-styles).

<div id="extending-color-styles"></div>

## Rozszerzanie stylow kolorow

Trzy domyslne grupy (`general`, `appBar`, `bottomTabBar`) sa punktem wyjscia, nie sztywnym ograniczeniem. Plik `lib/resources/themes/color_styles.dart` mozesz modyfikowac — dodawaj nowe grupy kolorow (lub pojedyncze pola) ponad domyslne, a nastepnie implementuj je w klasie kolorow kazdego motywu.

**1. Zdefiniuj niestandardowa grupe kolorow**

Zgrupuj powiazane kolory w malej, niezmiennej klasie:

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

**2. Dodaj ja do `ColorStyles`**

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

  // Niestandardowe grupy
  IconColors get icons;
}
```

**3. Zaimplementuj ja w klasie kolorow kazdego motywu**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...istniejace nadpisania...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Powtorz to samo nadpisanie `icons` w `dark_theme_colors.dart` z wartosciami dla trybu ciemnego.

**4. Uzyj w swoich widgetach**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Przelaczanie motywu

{{ config('app.name') }} obsluguje mozliwosc przelaczania motywow w locie.

Np. jesli musisz przelaczac motyw, gdy uzytkownik kliknie przycisk, aby aktywowac "ciemny motyw".

Mozesz to obslugiwac w nastepujacy sposob:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // ustaw motyw na "ciemny motyw"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// lub

TextButton(onPressed: () {

    // ustaw motyw na "jasny motyw"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Czcionki

Aktualizacja glownej czcionki w calej aplikacji jest latwa w {{ config('app.name') }}. Otworz plik `lib/config/design.dart` i zaktualizuj `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Dolaczamy biblioteke <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> do repozytorium, wiec mozesz zaczac uzywac wszystkich czcionek z minimalnym wysilkiem. Aby zmienic czcionke na inna czcionke Google, zmien wywolanie:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Sprawdz czcionki na oficjalnej stronie biblioteki <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a>, aby dowiedziec sie wiecej.

Potrzebujesz uzyc niestandardowej czcionki? Sprawdz ten przewodnik - https://flutter.dev/docs/cookbook/design/fonts

Po dodaniu czcionki zmien zmienna jak w ponizszym przykladzie.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo uzyte jako przyklad niestandardowej czcionki
```

<div id="design"></div>

## Design

Plik **lib/config/design.dart** sluzy do zarzadzania elementami projektu aplikacji. Wszystko jest dostepne przez klase `DesignConfig`:

`DesignConfig.appFont` zawiera czcionke aplikacji.

`DesignConfig.logo` sluzy do wyswietlania logo aplikacji.

Mozesz zmodyfikowac **lib/resources/widgets/logo_widget.dart**, aby dostosowac sposob wyswietlania logo.

`DesignConfig.loader` sluzy do wyswietlania loadera. {{ config('app.name') }} uzyje tej zmiennej w niektorych metodach pomocniczych jako domyslny widget loadera.

Mozesz zmodyfikowac **lib/resources/widgets/loader_widget.dart**, aby dostosowac sposob wyswietlania loadera.

<div id="text-extensions"></div>

## Rozszerzenia tekstu

Oto dostepne rozszerzenia tekstu, ktorych mozesz uzywac w {{ config('app.name') }}.

| Nazwa reguly   | Uzycie | Informacja |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Stosuje textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Stosuje textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Stosuje textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Stosuje textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Stosuje textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Stosuje textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Stosuje textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Stosuje textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Stosuje textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Stosuje textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Stosuje textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Stosuje textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Stosuje textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Stosuje textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Stosuje textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Grubosc czcionki pogrubiona</a> | fontWeightBold  | Stosuje pogrubiona grubosc czcionki do widgetu Text |
| <a href="#text-extension-font-weight-bold">Grubosc czcionki lekka</a> | fontWeightLight  | Stosuje lekka grubosc czcionki do widgetu Text |
| <a href="#text-extension-set-color">Ustaw kolor</a> | setColor(context, (color) => colors.primaryAccent)  | Ustaw inny kolor tekstu na widgecie Text |
| <a href="#text-extension-align-left">Wyrownaj do lewej</a> | alignLeft  | Wyrownaj czcionke do lewej |
| <a href="#text-extension-align-right">Wyrownaj do prawej</a> | alignRight  | Wyrownaj czcionke do prawej |
| <a href="#text-extension-align-center">Wyrownaj do srodka</a> | alignCenter  | Wyrownaj czcionke do srodka |
| <a href="#text-extension-set-max-lines">Ustaw maksymalna liczbe linii</a> | setMaxLines(int maxLines)  | Ustaw maksymalna liczbe linii dla widgetu tekstu |

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

#### Grubosc czcionki pogrubiona

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Grubosc czcionki lekka

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Ustaw kolor

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Kolor z Twoich colorStyles
```

<div id="text-extension-align-left"></div>

#### Wyrownaj do lewej

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Wyrownaj do prawej

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Wyrownaj do srodka

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Ustaw maksymalna liczbe linii

``` dart
Text("Hello World").setMaxLines(5)
```
