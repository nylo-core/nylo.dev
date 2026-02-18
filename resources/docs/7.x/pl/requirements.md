# Requirements

---

<a name="section-1"></a>
- [Wymagania systemowe](#system-requirements "Wymagania systemowe")
- [Instalacja Flutter](#installing-flutter "Instalacja Flutter")
- [Weryfikacja instalacji](#verifying-installation "Weryfikacja instalacji")
- [Konfiguracja edytora](#set-up-an-editor "Konfiguracja edytora")


<div id="system-requirements"></div>

## Wymagania systemowe

{{ config('app.name') }} v7 wymaga nastepujacych minimalnych wersji:

| Wymaganie | Minimalna wersja |
|-------------|-----------------|
| **Flutter** | 3.24.0 lub wyzsza |
| **Dart SDK** | 3.10.7 lub wyzsza |

### Wsparcie platform

{{ config('app.name') }} wspiera wszystkie platformy obslugiwane przez Flutter:

| Platforma | Wsparcie |
|----------|---------|
| iOS | ✓ Pelne wsparcie |
| Android | ✓ Pelne wsparcie |
| Web | ✓ Pelne wsparcie |
| macOS | ✓ Pelne wsparcie |
| Windows | ✓ Pelne wsparcie |
| Linux | ✓ Pelne wsparcie |

<div id="installing-flutter"></div>

## Instalacja Flutter

Jesli nie masz zainstalowanego Flutter, postepuj zgodnie z oficjalnym przewodnikiem instalacji dla swojego systemu operacyjnego:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Przewodnik instalacji Flutter</a>

<div id="verifying-installation"></div>

## Weryfikacja instalacji

Po zainstalowaniu Flutter zweryfikuj swoja konfiguracje:

### Sprawdz wersje Flutter

``` bash
flutter --version
```

Powinienes zobaczyc wynik podobny do:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Zaktualizuj Flutter (jesli potrzeba)

Jesli Twoja wersja Flutter jest nizsza niz 3.24.0, zaktualizuj do najnowszego stabilnego wydania:

``` bash
flutter channel stable
flutter upgrade
```

### Uruchom Flutter Doctor

Zweryfikuj, czy Twoje srodowisko deweloperskie jest poprawnie skonfigurowane:

``` bash
flutter doctor -v
```

To polecenie sprawdza:
- Instalacje Flutter SDK
- Lancuch narzedzi Android (do rozwoju na Androida)
- Xcode (do rozwoju na iOS/macOS)
- Podlaczone urzadzenia
- Wtyczki IDE

Napraw wszelkie zglaszane problemy przed przystąpieniem do instalacji {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Konfiguracja edytora

Wybierz IDE z obsluga Flutter:

### Visual Studio Code (zalecany)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> jest lekki i ma doskonale wsparcie dla Flutter.

1. Zainstaluj <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Zainstaluj <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">rozszerzenie Flutter</a>
3. Zainstaluj <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">rozszerzenie Dart</a>

Przewodnik konfiguracji: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Konfiguracja Flutter w VS Code</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> zapewnia pelne IDE z wbudowana obsluga emulatora.

1. Zainstaluj <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Zainstaluj wtyczke Flutter (Preferences → Plugins → Flutter)
3. Zainstaluj wtyczke Dart

Przewodnik konfiguracji: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Konfiguracja Flutter w Android Studio</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community lub Ultimate) rowniez wspiera rozwoj z Flutter.

1. Zainstaluj IntelliJ IDEA
2. Zainstaluj wtyczke Flutter (Preferences → Plugins → Flutter)
3. Zainstaluj wtyczke Dart

Gdy Twoj edytor jest skonfigurowany, mozesz przejsc do [instalacji {{ config('app.name') }}](/docs/7.x/installation).