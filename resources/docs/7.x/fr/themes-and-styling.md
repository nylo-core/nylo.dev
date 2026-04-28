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
  - [Etendre les styles de couleur](#extending-color-styles "Etendre les styles de couleur")
  - [Changer de theme](#switching-theme "Changer de theme")
  - [Polices](#fonts "Polices")
  - [Design](#design "Design")
- [Extensions de texte](#text-extensions "Extensions de texte")


<div id="introduction"></div>

## Introduction

Vous pouvez gerer les styles d'interface de votre application en utilisant les themes. Les themes nous permettent de modifier par exemple la taille de police du texte, l'apparence des boutons et l'apparence generale de notre application.

Si vous etes nouveau avec les themes, les exemples sur le site web de Flutter vous aideront a commencer <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">ici</a>.

Par defaut, {{ config('app.name') }} inclut des themes preconfigures pour le `Light mode` et le `Dark mode`.

Le theme se mettra egalement a jour si l'appareil passe en mode <b>'clair/sombre'</b>.

<div id="light-and-dark-themes"></div>

## Themes clair et sombre

Chaque theme se trouve dans son propre sous-repertoire sous `lib/resources/themes/` :

- Theme clair – `lib/resources/themes/light/light_theme.dart`
- Couleurs claires – `lib/resources/themes/light/light_theme_colors.dart`
- Theme sombre – `lib/resources/themes/dark/dark_theme.dart`
- Couleurs sombres – `lib/resources/themes/dark/dark_theme_colors.dart`

Les deux themes partagent un builder commun dans `lib/resources/themes/base_theme.dart` et l'interface `ColorStyles` dans `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Creer un theme

Si vous souhaitez avoir plusieurs themes pour votre application, nous avons un moyen facile de le faire. Si vous etes nouveau avec les themes, suivez le guide.

Tout d'abord, executez la commande ci-dessous depuis le terminal

``` bash
dart run nylo_framework:main make:theme bright_theme
```

<b>Note :</b> remplacez **bright_theme** par le nom de votre nouveau theme.

Cela cree un nouveau repertoire de theme dans `lib/resources/themes/bright/` contenant `bright_theme.dart` et `bright_theme_colors.dart`, puis l'enregistre dans `lib/bootstrap/theme.dart`.

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

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

Vous pouvez modifier les couleurs de votre nouveau theme dans le fichier **lib/resources/themes/bright/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Couleurs du theme

Pour gerer les couleurs du theme dans votre projet, consultez les repertoires `lib/resources/themes/light/` et `lib/resources/themes/dark/`. Chacun contient le fichier de couleurs de son theme — `light_theme_colors.dart` et `dark_theme_colors.dart`.

Les valeurs de couleur sont organisees en groupes (`general`, `appBar`, `bottomTabBar`) definis par le framework. La classe de couleurs de votre theme etend `ColorStyles` et fournit une instance de chaque groupe :

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Couleurs pour usage general.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Couleurs pour la barre d'application.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Couleurs pour la barre d'onglets inferieure.
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

## Utiliser les couleurs dans les widgets

Utilisez le helper `nyColorStyle<T>(context)` pour lire les couleurs du theme actif. Passez le type `ColorStyles` de votre projet afin que l'appel soit entierement type :

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// dans le build d'un widget :
final colors = nyColorStyle<ColorStyles>(context);

// la couleur de fond du theme actif
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Lire les couleurs d'un theme specifique (quel que soit celui qui est actif) :
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Styles de base

Les styles de base vous permettent de decrire chaque theme via une seule interface. {{ config('app.name') }} est livre avec `lib/resources/themes/color_styles.dart`, qui est le contrat qu'implementent a la fois `light_theme_colors.dart` et `dark_theme_colors.dart`.

`ColorStyles` etend `ThemeColor` du framework, qui expose trois groupes de couleurs predefinis : `GeneralColors`, `AppBarColors` et `BottomTabBarColors`. Le builder de theme de base (`lib/resources/themes/base_theme.dart`) lit ces groupes lors de la construction de `ThemeData`, de sorte que tout ce que vous y placez est automatiquement relie aux widgets correspondants.

<br>

Fichier `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Couleurs pour usage general.
  @override
  GeneralColors get general;

  /// Couleurs pour la barre d'application.
  @override
  AppBarColors get appBar;

  /// Couleurs pour la barre d'onglets inferieure.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Les trois groupes exposent les champs suivants :

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Pour ajouter des champs au-dela de ces valeurs par defaut — vos propres boutons, icones, badges, etc. — voir [Etendre les styles de couleur](#extending-color-styles).

<div id="extending-color-styles"></div>

## Etendre les styles de couleur

<!-- uncertain: new section "Extending color styles" not present in previous locale file -->
Les trois groupes par defaut (`general`, `appBar`, `bottomTabBar`) sont un point de depart, pas une limite absolue. `lib/resources/themes/color_styles.dart` vous appartient pour modification — ajoutez de nouveaux groupes de couleurs (ou des champs individuels) en plus des valeurs par defaut, puis implementez-les dans la classe de couleurs de chaque theme.

**1. Definir un groupe de couleurs personnalise**

Regroupez les couleurs liees dans une petite classe immuable :

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

**2. L'ajouter a `ColorStyles`**

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

  // Groupes personnalises
  IconColors get icons;
}
```

**3. L'implementer dans la classe de couleurs de chaque theme**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...surcharges existantes...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Repetez la meme surcharge `icons` dans `dark_theme_colors.dart` avec les valeurs du mode sombre.

**4. L'utiliser dans vos widgets**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Changer de theme

{{ config('app.name') }} prend en charge la possibilite de changer de theme a la volee.

Par exemple, si vous devez changer le theme lorsqu'un utilisateur appuie sur un bouton pour activer le "theme sombre".

Vous pouvez le faire de la maniere suivante :

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

## Polices

Mettre a jour votre police principale dans toute l'application est facile dans {{ config('app.name') }}. Ouvrez `lib/config/design.dart` et mettez a jour `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Nous incluons la bibliotheque <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> dans le depot, vous pouvez donc commencer a utiliser toutes les polices avec peu d'effort. Pour passer a une autre police Google, changez simplement l'appel :

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Consultez les polices sur la bibliotheque officielle <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> pour en savoir plus.

Besoin d'utiliser une police personnalisee ? Consultez ce guide - https://flutter.dev/docs/cookbook/design/fonts

Une fois que vous avez ajoute votre police, changez la variable comme dans l'exemple ci-dessous.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Design

Le fichier **lib/config/design.dart** est utilise pour gerer les elements de design de votre application. Tout est expose via la classe `DesignConfig` :

`DesignConfig.appFont` contient la police de votre application.

`DesignConfig.logo` est utilise pour afficher le logo de votre application.

Vous pouvez modifier **lib/resources/widgets/logo_widget.dart** pour personnaliser la facon dont vous souhaitez afficher votre logo.

`DesignConfig.loader` est utilise pour afficher un chargeur. {{ config('app.name') }} utilisera cette variable dans certaines methodes helper comme widget de chargement par defaut.

Vous pouvez modifier **lib/resources/widgets/loader_widget.dart** pour personnaliser la facon dont vous souhaitez afficher votre chargeur.

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
