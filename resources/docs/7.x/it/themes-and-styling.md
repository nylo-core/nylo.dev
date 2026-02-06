# Temi e Stili

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione ai temi")
- Temi
  - [Temi Chiaro e Scuro](#light-and-dark-themes "Temi chiaro e scuro")
  - [Creare un tema](#creating-a-theme "Creare un tema")
- Configurazione
  - [Colori del tema](#theme-colors "Colori del tema")
  - [Utilizzare i colori](#using-colors "Utilizzare i colori")
  - [Stili di base](#base-styles "Stili di base")
  - [Cambiare tema](#switching-theme "Cambiare tema")
  - [Font](#fonts "Font")
  - [Design](#design "Design")
- [Estensioni di Testo](#text-extensions "Estensioni di testo")


<div id="introduction"></div>

## Introduzione

Puoi gestire gli stili dell'interfaccia utente della tua applicazione utilizzando i temi. I temi ti permettono di cambiare, ad esempio, la dimensione del font del testo, l'aspetto dei pulsanti e l'aspetto generale della tua applicazione.

Se sei nuovo ai temi, gli esempi sul sito web di Flutter ti aiuteranno a iniziare <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">qui</a>.

Per impostazione predefinita, {{ config('app.name') }} include temi preconfigurati per la `Modalita' chiara` e la `Modalita' scura`.

Il tema si aggiornera' anche se il dispositivo entra in modalita' <b>'chiaro/scuro'</b>.

<div id="light-and-dark-themes"></div>

## Temi Chiaro e Scuro

- Tema chiaro - `lib/resources/themes/light_theme.dart`
- Tema scuro - `lib/resources/themes/dark_theme.dart`

All'interno di questi file, troverai ThemeData e ThemeStyle predefiniti.



<div id="creating-a-theme"></div>

## Creare un tema

Se vuoi avere temi multipli per la tua app, abbiamo un modo semplice per farlo. Se sei nuovo ai temi, segui la guida.

Per prima cosa, esegui il comando seguente dal terminale

``` bash
metro make:theme bright_theme
```

<b>Nota:</b> sostituisci **bright_theme** con il nome del tuo nuovo tema.

Questo crea un nuovo tema nella tua directory `/resources/themes/` e anche un file di colori del tema in `/resources/themes/styles/`.

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

Puoi modificare i colori per il tuo nuovo tema nel file **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Colori del Tema

Per gestire i colori del tema nel tuo progetto, controlla la directory `lib/resources/themes/styles`.
Questa directory contiene i colori degli stili per light_theme_colors.dart e dark_theme_colors.dart.

In questo file, dovresti avere qualcosa di simile a quanto segue.

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

## Utilizzare i colori nei widget

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

## Stili di Base

Gli stili di base ti permettono di personalizzare vari colori dei widget da un'unica area nel tuo codice.

{{ config('app.name') }} viene fornito con stili di base preconfigurati per il tuo progetto situati in `lib/resources/themes/styles/color_styles.dart`.

Questi stili forniscono un'interfaccia per i colori del tuo tema in `light_theme_colors.dart` e `dart_theme_colors.dart`.

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

Puoi aggiungere stili aggiuntivi qui e poi implementare i colori nel tuo tema.

<div id="switching-theme"></div>

## Cambiare tema

{{ config('app.name') }} supporta la possibilita' di cambiare tema al volo.

Ad esempio, se hai bisogno di cambiare tema quando un utente tocca un pulsante per attivare il "tema scuro".

Puoi supportare questo facendo come segue:

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

## Font

Aggiornare il font principale in tutta l'app e' facile in {{ config('app.name') }}. Apri il file `lib/config/design.dart` e aggiorna quanto segue.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Includiamo la libreria <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> nel repository, quindi puoi iniziare a usare tutti i font con poco sforzo.
Per aggiornare il font a qualcos'altro, puoi fare quanto segue:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Consulta i font sulla libreria ufficiale <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> per saperne di piu'

Hai bisogno di usare un font personalizzato? Consulta questa guida - https://flutter.dev/docs/cookbook/design/fonts

Una volta aggiunto il tuo font, modifica la variabile come nell'esempio seguente.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Design

Il file **config/design.dart** viene utilizzato per gestire gli elementi di design della tua app.

La variabile `appFont` contiene il font per la tua app.

La variabile `logo` viene utilizzata per visualizzare il Logo della tua app.

Puoi modificare **resources/widgets/logo_widget.dart** per personalizzare come vuoi visualizzare il tuo Logo.

La variabile `loader` viene utilizzata per visualizzare un loader. {{ config('app.name') }} utilizzera' questa variabile in alcuni metodi helper come widget loader predefinito.

Puoi modificare **resources/widgets/loader_widget.dart** per personalizzare come vuoi visualizzare il tuo Loader.

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
