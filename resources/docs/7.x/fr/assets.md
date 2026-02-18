# Assets

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Fichiers
  - [Afficher des images](#displaying-images "Afficher des images")
  - [Chemins d'assets personnalises](#custom-asset-paths "Chemins d'assets personnalises")
  - [Retourner les chemins d'assets](#returning-asset-paths "Retourner les chemins d'assets")
- Gerer les assets
  - [Ajouter de nouveaux fichiers](#adding-new-files "Ajouter de nouveaux fichiers")
  - [Configuration des assets](#asset-configuration "Configuration des assets")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 fournit des methodes d'aide pour gerer les assets dans votre application Flutter. Les assets sont stockes dans le repertoire `assets/` et incluent les images, videos, polices et autres fichiers.

La structure d'assets par defaut :

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Afficher des images

Utilisez le widget `LocalAsset()` pour afficher des images depuis les assets :

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Les deux methodes retournent le chemin complet de l'asset incluant le repertoire d'assets configure.

<div id="custom-asset-paths"></div>

## Chemins d'assets personnalises

Pour prendre en charge differents sous-repertoires d'assets, vous pouvez ajouter des constructeurs personnalises au widget `LocalAsset`.

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- new constructor for icons folder
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// Usage examples
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## Retourner les chemins d'assets

Utilisez `getAsset()` pour tout type de fichier dans le repertoire `assets/` :

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Utilisation avec differents widgets

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Ajouter de nouveaux fichiers

1. Placez vos fichiers dans le sous-repertoire approprie de `assets/` :
   - Images : `assets/images/`
   - Videos : `assets/videos/`
   - Polices : `assets/fonts/`
   - Autres : `assets/data/` ou dossiers personnalises

2. Assurez-vous que le dossier est liste dans `pubspec.yaml` :

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Configuration des assets

{{ config('app.name') }} v7 configure le chemin des assets via la variable d'environnement `ASSET_PATH` dans votre fichier `.env` :

``` bash
ASSET_PATH="assets"
```

Les fonctions d'aide ajoutent automatiquement ce chemin en prefixe, donc vous n'avez pas besoin d'inclure `assets/` dans vos appels :

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Modifier le chemin de base

Si vous avez besoin d'une structure d'assets differente, mettez a jour `ASSET_PATH` dans votre `.env` :

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Apres la modification, regenerez votre configuration d'environnement :

``` bash
metro make:env --force
```

