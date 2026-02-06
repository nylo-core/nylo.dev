# Styled Text

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Mode enfants](#children-mode "Mode enfants")
- [Mode template](#template-mode "Mode template")
  - [Styliser les espaces reserves](#styling-placeholders "Styliser les espaces reserves")
  - [Callbacks au toucher](#tap-callbacks "Callbacks au toucher")
  - [Cles separees par des barres verticales](#pipe-keys "Cles separees par des barres verticales")
- [Parametres](#parameters "Parametres")
- [Extensions de Text](#text-extensions "Extensions de Text")
  - [Styles typographiques](#typography-styles "Styles typographiques")
  - [Methodes utilitaires](#utility-methods "Methodes utilitaires")
- [Exemples](#examples "Exemples pratiques")

<div id="introduction"></div>

## Introduction

**StyledText** est un widget pour afficher du texte enrichi avec des styles mixtes, des callbacks au toucher et des evenements de pointeur. Il se rend sous forme d'un widget `RichText` avec plusieurs enfants `TextSpan`, vous donnant un controle precis sur chaque segment de texte.

StyledText prend en charge deux modes :

1. **Mode enfants** -- passez une liste de widgets `Text`, chacun avec son propre style
2. **Mode template** -- utilisez la syntaxe `@{{espace reserve}}` dans une chaine et associez les espaces reserves a des styles et des actions

<div id="basic-usage"></div>

## Utilisation de base

``` dart
// Children mode - list of Text widgets
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Template mode - placeholder syntax
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Mode enfants

Passez une liste de widgets `Text` pour composer du texte stylise :

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

Le `style` de base est applique a tout enfant qui n'a pas son propre style.

### Evenements de pointeur

Detectez quand le pointeur entre ou sort d'un segment de texte :

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## Mode template

Utilisez `StyledText.template()` avec la syntaxe `@{{espace reserve}}` :

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

Le texte entre `@{{ }}` est a la fois le **texte affiche** et la **cle** utilisee pour rechercher les styles et les callbacks au toucher.

<div id="styling-placeholders"></div>

### Styliser les espaces reserves

Associez les noms des espaces reserves a des objets `TextStyle` :

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### Callbacks au toucher

Associez les noms des espaces reserves a des gestionnaires de toucher :

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### Cles separees par des barres verticales

Appliquez le meme style ou callback a plusieurs espaces reserves en utilisant des cles separees par le caractere pipe `|` :

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

Cela associe le meme style et callback aux trois espaces reserves.

<div id="parameters"></div>

## Parametres

### StyledText (Mode enfants)

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `children` | `List<Text>` | requis | Liste de widgets Text |
| `style` | `TextStyle?` | null | Style de base pour tous les enfants |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Callback d'entree du pointeur |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Callback de sortie du pointeur |
| `spellOut` | `bool?` | null | Epeler le texte caractere par caractere |
| `softWrap` | `bool` | `true` | Activer le retour a la ligne souple |
| `textAlign` | `TextAlign` | `TextAlign.start` | Alignement du texte |
| `textDirection` | `TextDirection?` | null | Direction du texte |
| `maxLines` | `int?` | null | Nombre maximum de lignes |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Comportement de debordement |
| `locale` | `Locale?` | null | Locale du texte |
| `strutStyle` | `StrutStyle?` | null | Style de jambage |
| `textScaler` | `TextScaler?` | null | Mise a l'echelle du texte |
| `selectionColor` | `Color?` | null | Couleur de surbrillance de la selection |

### StyledText.template (Mode template)

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `text` | `String` | requis | Texte template avec la syntaxe `@{{espace reserve}}` |
| `styles` | `Map<String, TextStyle>?` | null | Association des noms d'espaces reserves aux styles |
| `onTap` | `Map<String, VoidCallback>?` | null | Association des noms d'espaces reserves aux callbacks au toucher |
| `style` | `TextStyle?` | null | Style de base pour le texte hors espaces reserves |

Tous les autres parametres (`softWrap`, `textAlign`, `maxLines`, etc.) sont les memes que pour le constructeur enfants.

<div id="text-extensions"></div>

## Extensions de Text

{{ config('app.name') }} etend le widget `Text` de Flutter avec des methodes typographiques et utilitaires.

<div id="typography-styles"></div>

### Styles typographiques

Appliquez les styles typographiques Material Design a n'importe quel widget `Text` :

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

Chacun accepte des surcharges optionnelles :

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Surcharges disponibles** (identiques pour toutes les methodes typographiques) :

| Parametre | Type | Description |
|-----------|------|-------------|
| `color` | `Color?` | Couleur du texte |
| `fontSize` | `double?` | Taille de la police |
| `fontWeight` | `FontWeight?` | Graisse de la police |
| `fontStyle` | `FontStyle?` | Italique/normal |
| `letterSpacing` | `double?` | Espacement des lettres |
| `wordSpacing` | `double?` | Espacement des mots |
| `height` | `double?` | Hauteur de ligne |
| `decoration` | `TextDecoration?` | Decoration du texte |
| `decorationColor` | `Color?` | Couleur de la decoration |
| `decorationStyle` | `TextDecorationStyle?` | Style de la decoration |
| `decorationThickness` | `double?` | Epaisseur de la decoration |
| `fontFamily` | `String?` | Famille de police |
| `shadows` | `List<Shadow>?` | Ombres du texte |
| `overflow` | `TextOverflow?` | Comportement de debordement |

<div id="utility-methods"></div>

### Methodes utilitaires

``` dart
// Font weight
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Alignment
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Max lines
Text("Long text...").setMaxLines(2)

// Font family
Text("Custom font").setFontFamily("Roboto")

// Font size
Text("Big text").setFontSize(24)

// Custom style
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Copy with modifications
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Exemples

### Lien vers les conditions generales

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### Affichage de version

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### Paragraphe a styles mixtes

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### Chaine typographique

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
