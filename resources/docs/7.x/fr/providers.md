# Providers

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creer un provider](#create-a-provider "Creer un provider")
- [Objet Provider](#provider-object "Objet Provider")


<div id="introduction"></div>

## Introduction aux Providers

Dans {{ config('app.name') }}, les providers sont initialises depuis votre fichier <b>main.dart</b> lorsque votre application demarre. Tous vos providers se trouvent dans `/lib/app/providers/*`, vous pouvez modifier ces fichiers ou creer vos propres providers en utilisant <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

Les providers peuvent etre utilises lorsque vous avez besoin d'initialiser une classe, un package ou de creer quelque chose avant que l'application ne se charge initialement. Par exemple, la classe `route_provider.dart` est responsable de l'ajout de toutes les routes dans {{ config('app.name') }}.

### Plongee en profondeur

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Cycle de vie

- `Boot.{{ config('app.name') }}` parcourra vos providers enregistres dans le fichier <b>config/providers.dart</b> et les initialisera.

- `Boot.Finished` est appele juste apres que **"Boot.{{ config('app.name') }}"** soit termine, cette methode liera l'instance {{ config('app.name') }} a `Backpack` avec la valeur 'nylo'.

Par exemple : Backpack.instance.read('nylo'); // instance {{ config('app.name') }}


<div id="create-a-provider"></div>

## Creer un nouveau Provider

Vous pouvez creer de nouveaux providers en executant la commande suivante dans le terminal.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Objet Provider

Votre provider aura deux methodes, `setup(Nylo nylo)` et `boot(Nylo nylo)`.

Lorsque l'application s'execute pour la premiere fois, tout code a l'interieur de votre methode **setup** sera execute en premier. Vous pouvez egalement manipuler l'objet `Nylo` comme dans l'exemple ci-dessous.

Exemple : `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Cycle de vie

1. `setup(Nylo nylo)` - Initialisez votre provider. Retournez l'instance `Nylo` ou `null`.
2. `boot(Nylo nylo)` - Appele apres que tous les providers aient termine leur setup. Utilisez ceci pour l'initialisation qui depend de la disponibilite d'autres providers.

> A l'interieur de la methode `setup`, vous devez **retourner** une instance de `Nylo` ou `null` comme ci-dessus.
