# Icones d'application

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Generer les icones d'application](#generating-app-icons "Generer les icones d'application")
- [Ajouter votre icone d'application](#adding-your-app-icon "Ajouter votre icone d'application")
- [Exigences pour les icones d'application](#app-icon-requirements "Exigences pour les icones d'application")
- [Configuration](#configuration "Configuration")
- [Compteur de badges](#badge-count "Compteur de badges")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 utilise <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> pour generer les icones d'application pour iOS et Android a partir d'une seule image source.

Votre icone d'application doit etre placee dans le repertoire `assets/app_icon/` avec une taille de **1024x1024 pixels**.

<div id="generating-app-icons"></div>

## Generer les icones d'application

Executez la commande suivante pour generer les icones pour toutes les plateformes :

``` bash
dart run flutter_launcher_icons
```

Cela lit votre icone source depuis `assets/app_icon/` et genere :
- Les icones iOS dans `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Les icones Android dans `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Ajouter votre icone d'application

1. Creez votre icone sous forme de fichier **PNG 1024x1024**
2. Placez-la dans `assets/app_icon/` (par exemple, `assets/app_icon/icon.png`)
3. Mettez a jour le `image_path` dans votre `pubspec.yaml` si necessaire :

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Executez la commande de generation d'icones

<div id="app-icon-requirements"></div>

## Exigences pour les icones d'application

| Attribut | Valeur |
|-----------|-------|
| Format | PNG |
| Taille | 1024x1024 pixels |
| Calques | Aplatis sans transparence |

### Nommage des fichiers

Gardez des noms de fichiers simples sans caracteres speciaux :
- `app_icon.png`
- `icon.png`

### Directives des plateformes

Pour des exigences detaillees, consultez les directives officielles des plateformes :
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Directives d'interface humaine Apple - Icones d'application</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Specifications de conception d'icones Google Play</a>

<div id="configuration"></div>

## Configuration

Personnalisez la generation d'icones dans votre `pubspec.yaml` :

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

Consultez la <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">documentation de flutter_launcher_icons</a> pour toutes les options disponibles.

<div id="badge-count"></div>

## Compteur de badges

{{ config('app.name') }} fournit des fonctions d'aide pour gerer les compteurs de badges d'application (le nombre affiche sur l'icone de l'application) :

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Support des plateformes

Les compteurs de badges sont supportes sur :
- **iOS** : Support natif
- **Android** : Necessite le support du lanceur (la plupart des lanceurs le supportent)
- **Web** : Non supporte

### Cas d'utilisation

Scenarios courants pour les compteurs de badges :
- Notifications non lues
- Messages en attente
- Articles dans le panier
- Taches incompletes

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```

