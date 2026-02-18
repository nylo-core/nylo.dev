# Providers

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Tworzenie providera](#create-a-provider "Tworzenie providera")
- [Obiekt providera](#provider-object "Obiekt providera")


<div id="introduction"></div>

## Wprowadzenie do providerów

W {{ config('app.name') }} providery są uruchamiane na początku z pliku <b>main.dart</b> podczas startu aplikacji. Wszystkie Twoje providery znajdują się w `/lib/app/providers/*`. Możesz modyfikować te pliki lub tworzyć własne providery za pomocą <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

Providery mogą być używane, gdy musisz zainicjalizować klasę, pakiet lub utworzyć coś przed pierwszym załadowaniem aplikacji. Na przykład klasa `route_provider.dart` jest odpowiedzialna za dodanie wszystkich tras do {{ config('app.name') }}.

### Szczegółowy przegląd

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

### Cykl życia

- `Boot.{{ config('app.name') }}` przejdzie przez zarejestrowane providery w pliku <b>config/providers.dart</b> i je uruchomi.

- `Boot.Finished` jest wywoływany zaraz po zakończeniu **"Boot.{{ config('app.name') }}"**. Ta metoda przypisze instancję {{ config('app.name') }} do `Backpack` z wartością 'nylo'.

Np. Backpack.instance.read('nylo'); // instancja {{ config('app.name') }}


<div id="create-a-provider"></div>

## Tworzenie nowego providera

Nowe providery możesz tworzyć, uruchamiając poniższe polecenie w terminalu.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Obiekt providera

Twój provider będzie miał dwie metody: `setup(Nylo nylo)` i `boot(Nylo nylo)`.

Gdy aplikacja uruchomi się po raz pierwszy, kod wewnątrz metody **setup** zostanie wykonany jako pierwszy. Możesz również manipulować obiektem `Nylo`, jak w poniższym przykładzie.

Przykład: `lib/app/providers/app_provider.dart`

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

### Cykl życia

1. `setup(Nylo nylo)` - Inicjalizacja providera. Zwróć instancję `Nylo` lub `null`.
2. `boot(Nylo nylo)` - Wywoływany po zakończeniu setup wszystkich providerów. Użyj tego do inicjalizacji zależnej od gotowości innych providerów.

> Wewnątrz metody `setup` musisz **zwrócić** instancję `Nylo` lub `null`, jak powyżej.
