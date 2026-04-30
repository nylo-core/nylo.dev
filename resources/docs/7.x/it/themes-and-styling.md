# Temi e Stili

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione ai temi")
- Temi
  - [Temi Chiaro e Scuro](#light-and-dark-themes "Temi Chiaro e Scuro")
  - [Creare un tema](#creating-a-theme "Creare un tema")
- Configurazione
  - [Colori del tema](#theme-colors "Colori del tema")
  - [Utilizzare i colori](#using-colors "Utilizzare i colori")
  - [Stili di base](#base-styles "Stili di base")
  - [Estendere gli stili di colore](#extending-color-styles "Estendere gli stili di colore")
  - [Cambiare tema](#switching-theme "Cambiare tema")
  - [Font](#fonts "Font")
  - [Design](#design "Design")
- [Estensioni di Testo](#text-extensions "Estensioni di Testo")


<div id="introduction"></div>

## Introduzione

Puoi gestire gli stili dell'interfaccia utente della tua applicazione utilizzando i temi. I temi ti permettono di cambiare, ad esempio, la dimensione del font del testo, l'aspetto dei pulsanti e l'aspetto generale della tua applicazione.

Se sei nuovo ai temi, gli esempi sul sito web di Flutter ti aiuteranno a iniziare <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">qui</a>.

Per impostazione predefinita, {{ config('app.name') }} include temi preconfigurati per la `Modalita' chiara` e la `Modalita' scura`.

Il tema si aggiornera' anche se il dispositivo entra in modalita' <b>'chiaro/scuro'</b>.

<div id="light-and-dark-themes"></div>

## Temi Chiaro e Scuro

Ogni tema risiede nella propria sottodirectory sotto `lib/resources/themes/`:

- Tema chiaro – `lib/resources/themes/light/light_theme.dart`
- Colori chiari – `lib/resources/themes/light/light_theme_colors.dart`
- Tema scuro – `lib/resources/themes/dark/dark_theme.dart`
- Colori scuri – `lib/resources/themes/dark/dark_theme_colors.dart`

Entrambi i temi condividono un builder comune in `lib/resources/themes/base_theme.dart` e l'interfaccia `ColorStyles` in `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Creare un tema

Se vuoi avere temi multipli per la tua app, crea i file del tema manualmente in `lib/resources/themes/`. I passi seguenti usano `bright` come esempio — sostituiscilo con il nome del tuo tema.

**Passo 1:** Crea il file del tema in `lib/resources/themes/bright/bright_theme.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**Passo 2:** Crea il file dei colori in `lib/resources/themes/bright/bright_theme_colors.dart`:

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

**Passo 3:** Registra il nuovo tema in `lib/bootstrap/theme.dart`.

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

Puoi modificare i colori nel file `bright_theme_colors.dart` per adattarli al tuo design.

<div id="theme-colors"></div>

## Colori del Tema

Per gestire i colori del tema nel tuo progetto, controlla le directory `lib/resources/themes/light/` e `lib/resources/themes/dark/`. Ognuna contiene il file dei colori per il suo tema — `light_theme_colors.dart` e `dark_theme_colors.dart`.

I valori dei colori sono organizzati in gruppi (`general`, `appBar`, `bottomTabBar`) definiti dal framework. La classe dei colori del tuo tema estende `ColorStyles` e fornisce un'istanza di ogni gruppo:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Colori per uso generale.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Colori per la barra dell'app.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Colori per la barra delle schede in basso.
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

## Utilizzare i colori nei widget

Usa l'helper `nyColorStyle<T>(context)` per leggere i colori del tema attivo. Passa il tipo `ColorStyles` del tuo progetto affinche' la chiamata sia completamente tipizzata:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// all'interno del build di un widget:
final colors = nyColorStyle<ColorStyles>(context);

// il colore di sfondo del tema attivo
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Leggi i colori da un tema specifico (indipendentemente da quale e' attivo):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Stili di Base

Gli stili di base ti permettono di descrivere ogni tema attraverso un'unica interfaccia. {{ config('app.name') }} viene fornito con `lib/resources/themes/color_styles.dart`, che e' il contratto implementato sia da `light_theme_colors.dart` che da `dark_theme_colors.dart`.

`ColorStyles` estende `ThemeColor` dal framework, che espone tre gruppi di colori predefiniti: `GeneralColors`, `AppBarColors` e `BottomTabBarColors`. Il builder del tema base (`lib/resources/themes/base_theme.dart`) legge questi gruppi quando costruisce `ThemeData`, quindi tutto cio' che inserisci in essi viene collegato automaticamente ai widget corrispondenti.

<br>

File `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Colori per uso generale.
  @override
  GeneralColors get general;

  /// Colori per la barra dell'app.
  @override
  AppBarColors get appBar;

  /// Colori per la barra delle schede in basso.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

I tre gruppi espongono i seguenti campi:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Per aggiungere campi oltre a questi predefiniti — pulsanti, icone, badge, ecc. — vedi [Estendere gli stili di colore](#extending-color-styles).

<div id="extending-color-styles"></div>

## Estendere gli stili di colore

<!-- uncertain: new section "Extending color styles" with new anchor #extending-color-styles — not present in the previous locale file -->
I tre gruppi predefiniti (`general`, `appBar`, `bottomTabBar`) sono un punto di partenza, non un limite fisso. `lib/resources/themes/color_styles.dart` e' tuo da modificare — aggiungi nuovi gruppi di colori (o singoli campi) sopra i predefiniti, poi implementali nella classe dei colori di ogni tema.

**1. Definisci un gruppo di colori personalizzato**

Raggruppa i colori correlati in una piccola classe immutabile:

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

**2. Aggiungilo a `ColorStyles`**

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

  // Gruppi personalizzati
  IconColors get icons;
}
```

**3. Implementalo nella classe dei colori di ogni tema**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...override esistenti...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Ripeti lo stesso override di `icons` in `dark_theme_colors.dart` con i valori per la modalita' scura.

**4. Usalo nei tuoi widget**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Cambiare tema

{{ config('app.name') }} supporta la possibilita' di cambiare tema al volo.

Ad esempio, se hai bisogno di cambiare tema quando un utente tocca un pulsante per attivare il "tema scuro".

Puoi supportare questo facendo come segue:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // imposta il tema su "tema scuro"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// oppure

TextButton(onPressed: () {

    // imposta il tema su "tema chiaro"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Font

Aggiornare il font principale in tutta l'app e' facile in {{ config('app.name') }}. Apri `lib/config/design.dart` e aggiorna `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Includiamo la libreria <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> nel repository, quindi puoi iniziare a usare tutti i font con poco sforzo. Per passare a un altro Google Font, basta cambiare la chiamata:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Consulta i font sulla libreria ufficiale <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> per saperne di piu'.

Hai bisogno di usare un font personalizzato? Consulta questa guida - https://flutter.dev/docs/cookbook/design/fonts

Una volta aggiunto il tuo font, modifica la variabile come nell'esempio seguente.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo usato come esempio per il font personalizzato
```

<div id="design"></div>

## Design

Il file **lib/config/design.dart** viene utilizzato per gestire gli elementi di design della tua app. Tutto e' esposto tramite la classe `DesignConfig`:

`DesignConfig.appFont` contiene il font per la tua app.

`DesignConfig.logo` viene utilizzato per visualizzare il Logo della tua app.

Puoi modificare **lib/resources/widgets/logo_widget.dart** per personalizzare come vuoi visualizzare il tuo Logo.

`DesignConfig.loader` viene utilizzato per visualizzare un loader. {{ config('app.name') }} utilizzera' questa variabile in alcuni metodi helper come widget loader predefinito.

Puoi modificare **lib/resources/widgets/loader_widget.dart** per personalizzare come vuoi visualizzare il tuo Loader.

<div id="text-extensions"></div>

## Estensioni di Testo

Ecco le estensioni di testo disponibili che puoi utilizzare in {{ config('app.name') }}.

| Nome Regola   | Utilizzo | Info |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Applica il textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Applica il textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Applica il textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Applica il textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Applica il textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Applica il textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Applica il textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Applica il textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Applica il textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Applica il textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Applica il textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Applica il textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Applica il textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Applica il textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Applica il textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Applica il grassetto al widget Text |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Applica il peso leggero al widget Text |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Imposta un colore di testo diverso sul widget Text |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Allinea il font a sinistra |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Allinea il font a destra |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Allinea il font al centro |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Imposta il numero massimo di righe per il widget text |

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
// Colore dai tuoi colorStyles
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
