# Voraussetzungen

---

<a name="section-1"></a>
- [Systemvoraussetzungen](#system-requirements "Systemvoraussetzungen")
- [Flutter installieren](#installing-flutter "Flutter installieren")
- [Installation ueberpruefen](#verifying-installation "Installation ueberpruefen")
- [Editor einrichten](#set-up-an-editor "Editor einrichten")


<div id="system-requirements"></div>

## Systemvoraussetzungen

{{ config('app.name') }} v7 erfordert die folgenden Mindestversionen:

| Voraussetzung | Mindestversion |
|---------------|----------------|
| **Flutter** | 3.24.0 oder hoeher |
| **Dart SDK** | 3.10.7 oder hoeher |

### Plattformunterstuetzung

{{ config('app.name') }} unterstuetzt alle Plattformen, die Flutter unterstuetzt:

| Plattform | Unterstuetzung |
|-----------|---------------|
| iOS | Volle Unterstuetzung |
| Android | Volle Unterstuetzung |
| Web | Volle Unterstuetzung |
| macOS | Volle Unterstuetzung |
| Windows | Volle Unterstuetzung |
| Linux | Volle Unterstuetzung |

<div id="installing-flutter"></div>

## Flutter installieren

Wenn Sie Flutter noch nicht installiert haben, folgen Sie der offiziellen Installationsanleitung fuer Ihr Betriebssystem:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter-Installationsanleitung</a>

<div id="verifying-installation"></div>

## Installation ueberpruefen

Ueberpruefen Sie nach der Installation von Flutter Ihr Setup:

### Flutter-Version pruefen

``` bash
flutter --version
```

Sie sollten eine aehnliche Ausgabe sehen:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Flutter aktualisieren (falls noetig)

Wenn Ihre Flutter-Version unter 3.24.0 liegt, aktualisieren Sie auf die neueste stabile Version:

``` bash
flutter channel stable
flutter upgrade
```

### Flutter Doctor ausfuehren

Ueberpruefen Sie, ob Ihre Entwicklungsumgebung korrekt konfiguriert ist:

``` bash
flutter doctor -v
```

Dieser Befehl prueft:
- Flutter SDK-Installation
- Android-Toolchain (fuer Android-Entwicklung)
- Xcode (fuer iOS/macOS-Entwicklung)
- Verbundene Geraete
- IDE-Plugins

Beheben Sie alle gemeldeten Probleme, bevor Sie mit der {{ config('app.name') }}-Installation fortfahren.

<div id="set-up-an-editor"></div>

## Editor einrichten

Waehlen Sie eine IDE mit Flutter-Unterstuetzung:

### Visual Studio Code (Empfohlen)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> ist leichtgewichtig und bietet hervorragende Flutter-Unterstuetzung.

1. Installieren Sie <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Installieren Sie die <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter-Extension</a>
3. Installieren Sie die <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart-Extension</a>

Einrichtungsanleitung: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter-Setup</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> bietet eine voll ausgestattete IDE mit integrierter Emulator-Unterstuetzung.

1. Installieren Sie <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Installieren Sie das Flutter-Plugin (Einstellungen -> Plugins -> Flutter)
3. Installieren Sie das Dart-Plugin

Einrichtungsanleitung: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter-Setup</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community oder Ultimate) unterstuetzt ebenfalls die Flutter-Entwicklung.

1. Installieren Sie IntelliJ IDEA
2. Installieren Sie das Flutter-Plugin (Einstellungen -> Plugins -> Flutter)
3. Installieren Sie das Dart-Plugin

Sobald Ihr Editor konfiguriert ist, sind Sie bereit, [{{ config('app.name') }} zu installieren](/docs/7.x/installation).
