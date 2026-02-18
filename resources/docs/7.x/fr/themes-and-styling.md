# Themes et style

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Themes
  - [Themes clair et sombre](#light-and-dark-themes "Themes clair et sombre")
  - [Creer un theme](#creating-a-theme "Creer un theme")
- Configuration
  - [Couleurs du theme](#theme-colors "Couleurs du theme")
  - [Utiliser les couleurs](#using-colors "Utiliser les couleurs")
  - [Styles de base](#base-styles "Styles de base")
  - [Changer de theme](#switching-theme "Changer de theme")
  - [Polices](#fonts "Polices")
  - [Design](#design "Design")
- [Extensions de texte](#text-extensions "Extensions de texte")


<div id="introduction"></div>

## Introduction

Vous pouvez gerer les styles d'interface de votre application en utilisant les themes. Les themes nous permettent de modifier par exemple la taille de police du texte, l'apparence des boutons et l'apparence generale de notre application.

Si vous etes nouveau avec les themes, les exemples sur le site web de Flutter vous aideront a commencer <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">ici</a>.

Par defaut, {{ config('app.name') }} inclut des themes preconfigures pour le `mode clair` et le `mode sombre`.

Le theme se mettra egalement a jour si l'appareil passe en mode <b>'clair/sombre'</b>.

<div id="light-and-dark-themes"></div>

## Themes clair et sombre

- Theme clair - `lib/resources/themes/light_theme.dart`
- Theme sombre - `lib/resources/themes/dark_theme.dart`

Dans ces fichiers, vous trouverez le ThemeData et le ThemeStyle predefinis.



<div id="creating-a-theme"></div>

## Creer un theme

Si vous souhaitez avoir plusieurs themes pour votre application, nous avons un moyen facile de le faire. Si vous etes nouveau avec les themes, suivez le guide.

Tout d'abord, executez la commande ci-dessous depuis le terminal

``` bash
metro make:theme bright_theme
```

<b>Note :</b> remplacez **bright_theme** par le nom de votre nouveau theme.

Cela cree un nouveau theme dans votre repertoire `/resources/themes/` ainsi qu'un fichier de couleurs de theme dans `/resources/themes/styles/`.

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

Vous pouvez modifier les couleurs de votre nouveau theme dans le fichier **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Couleurs du theme

Pour gerer les couleurs du theme dans votre projet, consultez le repertoire `lib/resources/themes/styles`.
Ce repertoire contient les couleurs de style pour light_theme_colors.dart et dark_theme_colors.dart.

Dans ce fichier, vous devriez avoir quelque chose de similaire a ce qui suit.

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

## Utiliser les couleurs dans les widgets

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

## Styles de base

Les styles de base vous permettent de personnaliser les couleurs de divers widgets depuis un seul endroit dans votre code.

{{ config('app.name') }} est livre avec des styles de base preconfigures pour votre projet situes dans `lib/resources/themes/styles/color_styles.dart`.

Ces styles fournissent une interface pour les couleurs de votre theme dans `light_theme_colors.dart` et `dart_theme_colors.dart`.

<br>

Fichier `lib/resources/themes/styles/color_styles.dart`

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

Vous pouvez ajouter des styles supplementaires ici puis implementer les couleurs dans votre theme.

<div id="switching-theme"></div>

## Changer de theme

{{ config('app.name') }} prend en charge la possibilite de changer de theme a la volee.

Par exemple, si vous devez changer le theme lorsqu'un utilisateur appuie sur un bouton pour activer le "theme sombre".

Vous pouvez le faire de la maniere suivante :

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

## Polices

Mettre a jour votre police principale dans toute l'application est facile dans {{ config('app.name') }}. Ouvrez le fichier `lib/config/design.dart` et mettez a jour ce qui suit.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Nous incluons la bibliotheque <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> dans le depot, vous pouvez donc commencer a utiliser toutes les polices avec peu d'effort.
Pour changer la police pour autre chose, vous pouvez faire ce qui suit :
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Consultez les polices sur la bibliotheque officielle <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> pour en savoir plus

Besoin d'utiliser une police personnalisee ? Consultez ce guide - https://flutter.dev/docs/cookbook/design/fonts

Une fois que vous avez ajoute votre police, changez la variable comme dans l'exemple ci-dessous.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Design

Le fichier **config/design.dart** est utilise pour gerer les elements de design de votre application.

La variable `appFont` contient la police de votre application.

La variable `logo` est utilisee pour afficher le logo de votre application.

Vous pouvez modifier **resources/widgets/logo_widget.dart** pour personnaliser la facon dont vous souhaitez afficher votre logo.

La variable `loader` est utilisee pour afficher un chargeur. {{ config('app.name') }} utilisera cette variable dans certaines methodes helper comme widget de chargement par defaut.

Vous pouvez modifier **resources/widgets/loader_widget.dart** pour personnaliser la facon dont vous souhaitez afficher votre chargeur.

<div id="text-extensions"></div>

## Extensions de texte

Voici les extensions de texte disponibles que vous pouvez utiliser dans {{ config('app.name') }}.

| Nom de la regle   | Utilisation | Info |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Applique le textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Applique le textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Applique le textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Applique le textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Applique le textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Applique le textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Applique le textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Applique le textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Applique le textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Applique le textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Applique le textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Applique le textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Applique le textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Applique le textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Applique le textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Applique le poids de police gras a un widget Text |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Applique le poids de police leger a un widget Text |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Definit une couleur de texte differente sur le widget Text |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Aligne la police a gauche |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Aligne la police a droite |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Aligne la police au centre |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Definit le nombre maximum de lignes pour le widget texte |

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
