# Provider

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Einen Provider erstellen](#create-a-provider "Einen Provider erstellen")
- [Provider-Objekt](#provider-object "Provider-Objekt")


<div id="introduction"></div>

## Einleitung zu Providern

In {{ config('app.name') }} werden Provider initial aus Ihrer <b>main.dart</b>-Datei gebootet, wenn Ihre Anwendung ausgeführt wird. Alle Ihre Provider befinden sich in `/lib/app/providers/*`, Sie können diese Dateien ändern oder Ihre Provider mit <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a> erstellen.

Provider können verwendet werden, wenn Sie eine Klasse, ein Paket initialisieren oder etwas erstellen müssen, bevor die App initial lädt. Z.B. ist die Klasse `route_provider.dart` dafür verantwortlich, alle Routen zu {{ config('app.name') }} hinzuzufügen.

### Vertiefung

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

### Lebenszyklus

- `Boot.{{ config('app.name') }}` durchläuft Ihre registrierten Provider in der <b>config/providers.dart</b>-Datei und bootet sie.

- `Boot.Finished` wird direkt nach **"Boot.{{ config('app.name') }}"** aufgerufen. Diese Methode bindet die {{ config('app.name') }}-Instanz an `Backpack` mit dem Wert 'nylo'.

Z.B. Backpack.instance.read('nylo'); // {{ config('app.name') }}-Instanz


<div id="create-a-provider"></div>

## Einen neuen Provider erstellen

Sie können neue Provider erstellen, indem Sie den folgenden Befehl im Terminal ausführen.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Provider-Objekt

Ihr Provider hat zwei Methoden: `setup(Nylo nylo)` und `boot(Nylo nylo)`.

Wenn die App zum ersten Mal ausgeführt wird, wird jeder Code innerhalb Ihrer **setup**-Methode zuerst ausgeführt. Sie können auch das `Nylo`-Objekt manipulieren, wie im folgenden Beispiel.

Beispiel: `lib/app/providers/app_provider.dart`

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

### Lebenszyklus

1. `setup(Nylo nylo)` - Initialisieren Sie Ihren Provider. Geben Sie die `Nylo`-Instanz oder `null` zurück.
2. `boot(Nylo nylo)` - Wird aufgerufen, nachdem alle Provider das Setup abgeschlossen haben. Verwenden Sie dies für Initialisierungen, die davon abhängen, dass andere Provider bereit sind.

> Innerhalb der `setup`-Methode müssen Sie eine Instanz von `Nylo` oder `null` **zurückgeben**, wie oben gezeigt.
