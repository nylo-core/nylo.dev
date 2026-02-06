# LanguageSwitcher

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Utilisation
    - [Widget Dropdown](#usage-dropdown "Widget Dropdown")
    - [Modal en bas de page](#usage-bottom-modal "Modal en bas de page")
- [Constructeur de dropdown personnalise](#custom-builder "Constructeur de dropdown personnalise")
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
        LanguageSwitcher(), // Add to the app bar
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

### Personnaliser la hauteur du modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

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
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
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
    // Perform additional actions when language changes
  },
)
```

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
// Returns: {"en": "English"} or null if not set
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
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Obtenir la liste des langues

Obtenir toutes les langues disponibles depuis le repertoire `/lang` :

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Afficher le modal en bas de page

Afficher le modal de selection de langue :

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
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
