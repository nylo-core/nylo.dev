# Prerequis

---

<a name="section-1"></a>
- [Configuration requise](#system-requirements "Configuration requise")
- [Installer Flutter](#installing-flutter "Installer Flutter")
- [Verifier votre installation](#verifying-installation "Verifier votre installation")
- [Configurer un editeur](#set-up-an-editor "Configurer un editeur")


<div id="system-requirements"></div>

## Configuration requise

{{ config('app.name') }} v7 necessite les versions minimales suivantes :

| Prerequis | Version minimale |
|-------------|-----------------|
| **Flutter** | 3.24.0 ou superieur |
| **Dart SDK** | 3.10.7 ou superieur |

### Support des plateformes

{{ config('app.name') }} prend en charge toutes les plateformes supportees par Flutter :

| Plateforme | Support |
|----------|---------|
| iOS | ✓ Support complet |
| Android | ✓ Support complet |
| Web | ✓ Support complet |
| macOS | ✓ Support complet |
| Windows | ✓ Support complet |
| Linux | ✓ Support complet |

<div id="installing-flutter"></div>

## Installer Flutter

Si vous n'avez pas Flutter installe, suivez le guide d'installation officiel pour votre systeme d'exploitation :

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Guide d'installation Flutter</a>

<div id="verifying-installation"></div>

## Verifier votre installation

Apres avoir installe Flutter, verifiez votre configuration :

### Verifier la version de Flutter

``` bash
flutter --version
```

Vous devriez voir une sortie similaire a :

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Mettre a jour Flutter (si necessaire)

Si votre version de Flutter est inferieure a 3.24.0, mettez a jour vers la derniere version stable :

``` bash
flutter channel stable
flutter upgrade
```

### Executer Flutter Doctor

Verifiez que votre environnement de developpement est correctement configure :

``` bash
flutter doctor -v
```

Cette commande verifie :
- L'installation du SDK Flutter
- La chaine d'outils Android (pour le developpement Android)
- Xcode (pour le developpement iOS/macOS)
- Les appareils connectes
- Les plugins IDE

Corrigez tous les problemes signales avant de proceder a l'installation de {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Configurer un editeur

Choisissez un IDE avec le support Flutter :

### Visual Studio Code (Recommande)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> est leger et offre un excellent support Flutter.

1. Installez <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Installez l'<a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">extension Flutter</a>
3. Installez l'<a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">extension Dart</a>

Guide de configuration : <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Configuration Flutter dans VS Code</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> fournit un IDE complet avec un support d'emulateur integre.

1. Installez <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Installez le plugin Flutter (Preferences → Plugins → Flutter)
3. Installez le plugin Dart

Guide de configuration : <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Configuration Flutter dans Android Studio</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community ou Ultimate) prend egalement en charge le developpement Flutter.

1. Installez IntelliJ IDEA
2. Installez le plugin Flutter (Preferences → Plugins → Flutter)
3. Installez le plugin Dart

Une fois votre editeur configure, vous etes pret a [installer {{ config('app.name') }}](/docs/7.x/installation).
