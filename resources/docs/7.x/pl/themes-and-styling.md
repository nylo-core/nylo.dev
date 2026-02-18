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

- Motyw jasny - `lib/resources/themes/light_theme.dart`
- Motyw ciemny - `lib/resources/themes/dark_theme.dart`

W tych plikach znajdziesz wstepnie zdefiniowane ThemeData i ThemeStyle.



<div id="creating-a-theme"></div>

## Tworzenie motywu

Jesli chcesz miec wiele motywow w swojej aplikacji, mamy latwy sposob, aby to zrobic. Jesli jestes nowy w temacie motywow, postepuj zgodnie z instrukcjami.

Najpierw uruchom ponizsze polecenie z terminala

``` bash
metro make:theme bright_theme
```

<b>Uwaga:</b> zastap **bright_theme** nazwa swojego nowego motywu.

To tworzy nowy motyw w katalogu `/resources/themes/` oraz plik kolorow motywu w `/resources/themes/styles/`.

``` dart
// Motywy aplikacji
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

  BaseThemeConfig<ColorStyles>( // nowy motyw dodany automatycznie
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

Mozesz zmodyfikowac kolory nowego motywu w pliku **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Kolory motywu

Aby zarzadzac kolorami motywu w projekcie, sprawdz katalog `lib/resources/themes/styles`.
Ten katalog zawiera kolory stylow dla light_theme_colors.dart i dark_theme_colors.dart.

W tym pliku powinienes miec cos podobnego do ponizszego.

``` dart
// np. kolory motywu jasnego
class LightThemeColors implements ColorStyles {
  // ogolne
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

  // pasek aplikacji
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // przyciski
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // dolny pasek zakladek
  @override
  Color get bottomTabBarBackground => Colors.white;

  // dolny pasek zakladek - ikony
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // dolny pasek zakladek - etykiety
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // powiadomienie toast
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## Uzycie kolorow w widgetach

``` dart
import 'package:flutter_app/config/theme.dart';
...

// pobiera kolor tla jasny/ciemny w zaleznosci od motywu
ThemeColor.get(context).background

// np. uzycie klasy "ThemeColor"
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - zawartosc
  ),
),

// lub

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Kolory motywu jasnego - glowna zawartosc
  ),
),
```

<div id="base-styles"></div>

## Style bazowe

Style bazowe pozwalaja dostosowac rozne kolory widgetow z jednego miejsca w kodzie.

{{ config('app.name') }} jest dostarczany z wstepnie skonfigurowanymi stylami bazowymi dla Twojego projektu w `lib/resources/themes/styles/color_styles.dart`.

Te style zapewniaja interfejs dla kolorow motywu w `light_theme_colors.dart` i `dart_theme_colors.dart`.

<br>

Plik `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // ogolne
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

  // pasek aplikacji
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

  // dolny pasek zakladek
  @override
  Color get bottomTabBarBackground;

  // dolny pasek zakladek - ikony
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // dolny pasek zakladek - etykiety
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // powiadomienie toast
  Color get toastNotificationBackground;
}
```

Mozesz dodac tutaj dodatkowe style, a nastepnie zaimplementowac kolory w swoim motywie.

<div id="switching-theme"></div>

## Przelaczanie motywu

{{ config('app.name') }} obsluguje mozliwosc przelaczania motywow w locie.

Np. jesli musisz przelaczac motyw, gdy uzytkownik kliknie przycisk, aby aktywowac "ciemny motyw".

Mozesz to obslugiwac w nastepujacy sposob:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
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

Aktualizacja glownej czcionki w calej aplikacji jest latwa w {{ config('app.name') }}. Otworz plik `lib/config/design.dart` i zaktualizuj ponizsze.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Dolaczamy biblioteke <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> do repozytorium, wiec mozesz zaczac uzywac wszystkich czcionek z minimalnym wysilkiem.
Aby zaktualizowac czcionke na inna, mozesz zrobic nastepujace:
``` dart
// STARE
// final TextStyle appThemeFont = GoogleFonts.lato();

// NOWE
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Sprawdz czcionki na oficjalnej stronie biblioteki <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a>, aby dowiedziec sie wiecej

Potrzebujesz uzyc niestandardowej czcionki? Sprawdz ten przewodnik - https://flutter.dev/docs/cookbook/design/fonts

Po dodaniu czcionki zmien zmienna jak w ponizszym przykladzie.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo uzyte jako przyklad niestandardowej czcionki
```

<div id="design"></div>

## Design

Plik **config/design.dart** sluzy do zarzadzania elementami projektu aplikacji.

Zmienna `appFont` zawiera czcionke aplikacji.

Zmienna `logo` sluzy do wyswietlania logo aplikacji.

Mozesz zmodyfikowac **resources/widgets/logo_widget.dart**, aby dostosowac sposob wyswietlania logo.

Zmienna `loader` sluzy do wyswietlania loadera. {{ config('app.name') }} uzyje tej zmiennej w niektorych metodach pomocniczych jako domyslny widget loadera.

Mozesz zmodyfikowac **resources/widgets/loader_widget.dart**, aby dostosowac sposob wyswietlania loadera.

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