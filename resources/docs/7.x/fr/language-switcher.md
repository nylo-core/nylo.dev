# LanguageSwitcher

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Utilisation
    - [Widget Dropdown](#usage-dropdown "Widget Dropdown")
    - [Modal en bas de page](#usage-bottom-modal "Modal en bas de page")
- [Style d'animation](#animation-style "Style d'animation")
- [Constructeur de dropdown personnalise](#custom-builder "Constructeur de dropdown personnalise")
- [Actions d'etat](#state-actions "Actions d'etat")
- [Parametres](#parameters "Parametres")
- [Methodes statiques](#methods "Methodes statiques")


<div id="introduction"></div>

## Introduction

Le widget **LanguageSwitcher** offre un moyen simple de gerer le changement de langue dans vos projets {{ config('app.name') }}. Il detecte automatiquement les langues disponibles dans votre repertoire `/lang` et les affiche a l'utilisateur.

**Que fait LanguageSwitcher ?**

- Affiche les langues disponibles depuis votre repertoire `/lang`
- Change la langue de l'application lorsque l'utilisateur en selectionne une
- Persiste la langue selectionnee entre les redemarrages de l'application
- Met automatiquement a jour l'interface lorsque la langue change

> **Note** : Si votre application n'est pas encore localisee, apprenez comment le faire dans la documentation de [Localisation](/docs/7.x/localization) avant d'utiliser ce widget.

<div id="usage-dropdown"></div>

## Widget Dropdown

La maniere la plus simple d'utiliser `LanguageSwitcher` est en tant que dropdown dans votre barre d'application :

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Ajouter a la barre d'application
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Lorsque l'utilisateur appuie sur le dropdown, il verra une liste des langues disponibles. Apres avoir selectionne une langue, l'application basculera automatiquement et mettra a jour l'interface.

<div id="usage-bottom-modal"></div>

## Modal en bas de page

Vous pouvez egalement afficher les langues dans un modal en bas de page :

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

Le modal en bas de page affiche une liste de langues avec une coche a cote de la langue actuellement selectionnee.

### Personnaliser le modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300,
  useRootNavigator: true, // Afficher le modal au-dessus de toutes les routes, y compris les barres d'onglets
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
);
```

<div id="animation-style"></div>

## Style d'animation

Le parametre `animationStyle` controle les animations de transition pour le declencheur du dropdown et les elements de la liste du modal. Quatre presets sont disponibles :

``` dart
// Aucune animation
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.none(),
)

// Animations subtiles et rafinees (recommandees pour la plupart des applications)
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
)

// Animations ludiques et rebondissantes
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.bouncy(),
)

// Fondu doux avec legere mise a l'echelle
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.fadeIn(),
)
```

Vous pouvez egalement passer un `LanguageSwitcherAnimationStyle()` personnalise avec des parametres individuels, ou utiliser `copyWith` pour ajuster un preset.

Le meme parametre `animationStyle` est accepte par `LanguageSwitcher.showBottomModal`.

<div id="custom-builder"></div>

## Constructeur de dropdown personnalise

Personnalisez l'apparence de chaque option de langue dans le dropdown :

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // par ex., "English"
        // language['locale'] contient le code de locale, par ex., "en"
      ],
    );
  },
)
```

### Gerer les changements de langue

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Effectuer des actions supplementaires lors du changement de langue
  },
)
```

<div id="state-actions"></div>

## Actions d'etat

Controlez le `LanguageSwitcher` par programmation avec `stateActions()` :

``` dart
// Actualiser la liste des langues (relit les langues disponibles)
LanguageSwitcher.stateActions().refresh();

// Changer de langue par code de locale
LanguageSwitcher.stateActions().setLanguage("es");
LanguageSwitcher.stateActions().setLanguage("fr");
```

Ceci est utile lorsque vous souhaitez changer la langue de l'application sans interaction utilisateur, par exemple apres la connexion avec une preference utilisateur.

<div id="parameters"></div>

## Parametres

| Parametre | Type | Par defaut | Description |
|-----------|------|------------|-------------|
| `icon` | `Widget?` | - | Icone personnalisee pour le bouton dropdown |
| `iconEnabledColor` | `Color?` | - | Couleur de l'icone du dropdown |
| `iconSize` | `double` | `24` | Taille de l'icone du dropdown |
| `dropdownBgColor` | `Color?` | - | Couleur d'arriere-plan du menu dropdown |
| `hint` | `Widget?` | - | Widget d'indication lorsqu'aucune langue n'est selectionnee |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Hauteur de chaque element du dropdown |
| `elevation` | `int` | `8` | Elevation du menu dropdown |
| `padding` | `EdgeInsetsGeometry?` | - | Espacement autour du dropdown |
| `borderRadius` | `BorderRadius?` | - | Rayon de bordure du menu dropdown |
| `textStyle` | `TextStyle?` | - | Style de texte pour les elements du dropdown |
| `langPath` | `String` | `'lang'` | Chemin vers les fichiers de langue dans les assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Constructeur personnalise pour les elements du dropdown |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Alignement des elements du dropdown |
| `dropdownOnTap` | `Function()?` | - | Callback lors de l'appui sur un element du dropdown |
| `onTap` | `Function()?` | - | Callback lors de l'appui sur le bouton dropdown |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback lors du changement de langue |

<div id="methods"></div>

## Methodes statiques

### Obtenir la langue actuelle

Recuperez la langue actuellement selectionnee :

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Retourne : {"en": "English"} ou null si non defini
```

### Stocker la langue

Stocker manuellement une preference de langue :

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Effacer la langue

Supprimer la preference de langue stockee :

``` dart
await LanguageSwitcher.clearLanguage();
```

### Obtenir les donnees de langue

Obtenir les informations de langue a partir d'un code de locale :

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Retourne : {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Retourne : {"fr_CA": "French (Canada)"}
```

### Obtenir la liste des langues

Obtenir toutes les langues disponibles depuis le repertoire `/lang` :

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Retourne : [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Afficher le modal en bas de page

Afficher le modal de selection de langue :

``` dart
await LanguageSwitcher.showBottomModal(context);

// Avec options
await LanguageSwitcher.showBottomModal(
  context,
  height: 400,
  useRootNavigator: true,
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
);
```

## Locales prises en charge

Le widget `LanguageSwitcher` prend en charge des centaines de codes de locale avec des noms lisibles. Quelques exemples :

| Code de locale | Nom de la langue |
|----------------|------------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

La liste complete inclut des variantes regionales pour la plupart des langues.
