# Structure des repertoires

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Repertoire racine](#root-directory "Repertoire racine")
- [Le repertoire lib](#lib-directory "Le repertoire lib")
  - [app](#app-directory "app")
  - [bootstrap](#bootstrap-directory "bootstrap")
  - [config](#config-directory "config")
  - [resources](#resources-directory "resources")
  - [routes](#routes-directory "routes")
- [Repertoire des assets](#assets-directory "Repertoire des assets")
- [Helpers pour les assets](#asset-helpers "Helpers pour les assets")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} utilise une structure de repertoires propre et organisee inspiree de <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Cette structure aide a maintenir la coherence entre les projets et facilite la recherche de fichiers.

<div id="root-directory"></div>

## Repertoire racine

```
nylo_app/
├── android/          # Android platform files
├── assets/           # Images, fonts, and other assets
├── ios/              # iOS platform files
├── lang/             # Language/translation JSON files
├── lib/              # Dart application code
├── test/             # Test files
├── .env              # Environment variables
├── pubspec.yaml      # Dependencies and project config
└── ...
```

<div id="lib-directory"></div>

## Le repertoire lib

Le dossier `lib/` contient tout le code Dart de votre application :

```
lib/
├── app/              # Application logic
├── bootstrap/        # Boot configuration
├── config/           # Configuration files
├── resources/        # UI components
├── routes/           # Route definitions
└── main.dart         # Application entry point
```

<div id="app-directory"></div>

### app/

Le repertoire `app/` contient la logique principale de votre application :

| Repertoire | Objectif |
|-----------|---------|
| `commands/` | Commandes Metro CLI personnalisees |
| `controllers/` | Controleurs de pages pour la logique metier |
| `events/` | Classes d'evenements pour le systeme d'evenements |
| `forms/` | Classes de formulaires avec validation |
| `models/` | Classes de modeles de donnees |
| `networking/` | Services API et configuration reseau |
| `networking/dio/interceptors/` | Intercepteurs HTTP Dio |
| `providers/` | Fournisseurs de services demarres au lancement de l'application |
| `services/` | Classes de services generaux |

<div id="bootstrap-directory"></div>

### bootstrap/

Le repertoire `bootstrap/` contient les fichiers qui configurent le demarrage de votre application :

| Fichier | Objectif |
|------|---------|
| `boot.dart` | Configuration de la sequence de demarrage principale |
| `decoders.dart` | Enregistrement des decodeurs de modeles et d'API |
| `env.g.dart` | Configuration d'environnement chiffree generee |
| `events.dart` | Enregistrement des evenements |
| `extensions.dart` | Extensions personnalisees |
| `helpers.dart` | Fonctions d'aide personnalisees |
| `providers.dart` | Enregistrement des fournisseurs |
| `theme.dart` | Configuration du theme |

<div id="config-directory"></div>

### config/

Le repertoire `config/` contient la configuration de l'application :

| Fichier | Objectif |
|------|---------|
| `app.dart` | Parametres principaux de l'application |
| `design.dart` | Design de l'application (police, logo, chargeur) |
| `localization.dart` | Parametres de langue et de localisation |
| `storage_keys.dart` | Definitions des cles de stockage local |
| `toast_notification.dart` | Styles de notifications toast |

<div id="resources-directory"></div>

### resources/

Le repertoire `resources/` contient les composants d'interface utilisateur :

| Repertoire | Objectif |
|-----------|---------|
| `pages/` | Widgets de pages (ecrans) |
| `themes/` | Definitions de themes |
| `themes/light/` | Couleurs du theme clair |
| `themes/dark/` | Couleurs du theme sombre |
| `widgets/` | Composants de widgets reutilisables |
| `widgets/buttons/` | Widgets de boutons personnalises |
| `widgets/bottom_sheet_modals/` | Widgets de feuilles modales inferieures |

<div id="routes-directory"></div>

### routes/

Le repertoire `routes/` contient la configuration du routage :

| Fichier/Repertoire | Objectif |
|----------------|---------|
| `router.dart` | Definitions des routes |
| `guards/` | Classes de gardes de routes |

<div id="assets-directory"></div>

## Repertoire des assets

Le repertoire `assets/` stocke les fichiers statiques :

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Enregistrement des assets

Les assets sont enregistres dans `pubspec.yaml` :

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Helpers pour les assets

{{ config('app.name') }} fournit des helpers pour travailler avec les assets.

### Assets d'images

``` dart
// Standard Flutter way
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Using LocalAsset widget
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Assets generaux

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Fichiers de langue

Les fichiers de langue sont stockes dans `lang/` a la racine du projet :

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Voir [Localisation](/docs/7.x/localization) pour plus de details.
