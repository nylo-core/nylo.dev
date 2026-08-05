# Voraussetzungen

---

<a name="section-1"></a>
- [Systemvoraussetzungen](#system-requirements "Systemvoraussetzungen")
- [Flutter installieren](#installing-flutter "Flutter installieren")
- [Installation überprüfen](#verifying-installation "Installation überprüfen")
- [Editor einrichten](#set-up-an-editor "Editor einrichten")


<div id="system-requirements"></div>

## Systemvoraussetzungen

<x-doc-strip label="Auf einen Blick" items="Flutter 3.38.4+, Dart SDK 3.10.7+, Alle Flutter-Plattformen" linkText="Flutter installieren" linkHref="https://docs.flutter.dev/get-started/install" />

{{ config('app.name') }} v7 erfordert die folgenden Mindestversionen:

| Voraussetzung | Mindestversion |
|---------------|----------------|
| **Flutter** | 3.38.4 oder höher |
| **Dart SDK** | 3.10.7 oder höher |

### Plattformunterstützung

{{ config('app.name') }} unterstützt alle Plattformen, die Flutter unterstützt:

| Plattform | Unterstützung |
|-----------|---------------|
| iOS | Volle Unterstützung |
| Android | Volle Unterstützung |
| Web | Volle Unterstützung |
| macOS | Volle Unterstützung |
| Windows | Volle Unterstützung |
| Linux | Volle Unterstützung |

<div id="installing-flutter"></div>

## Flutter installieren

Wenn Sie Flutter noch nicht installiert haben, folgen Sie der offiziellen Installationsanleitung für Ihr Betriebssystem:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter-Installationsanleitung</a>

<div id="verifying-installation"></div>

## Installation überprüfen

Überprüfen Sie nach der Installation von Flutter Ihr Setup:

### Flutter-Version prüfen

``` bash
flutter --version
```

Sie sollten eine ähnliche Ausgabe sehen:

```
Flutter 3.38.4 • channel stable
Dart SDK version: 3.10.7
```

### Flutter aktualisieren (falls nötig)

Wenn Ihre Flutter-Version unter 3.38.4 liegt, aktualisieren Sie auf die neueste stabile Version:

``` bash
flutter channel stable
flutter upgrade
```

### Flutter Doctor ausführen

Überprüfen Sie, ob Ihre Entwicklungsumgebung korrekt konfiguriert ist:

``` bash
flutter doctor -v
```

Dieser Befehl prüft:
- Flutter SDK-Installation
- Android-Toolchain (für Android-Entwicklung)
- Xcode (für iOS/macOS-Entwicklung)
- Verbundene Geräte
- IDE-Plugins

Beheben Sie alle gemeldeten Probleme, bevor Sie mit der {{ config('app.name') }}-Installation fortfahren.

<div id="set-up-an-editor"></div>

## Editor einrichten

Wählen Sie eine IDE mit Flutter-Unterstützung:

### Visual Studio Code (Empfohlen)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> ist leichtgewichtig und bietet hervorragende Flutter-Unterstützung.

1. Installieren Sie <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Installieren Sie die <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter-Extension</a>
3. Installieren Sie die <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart-Extension</a>

Einrichtungsanleitung: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter-Setup</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> bietet eine voll ausgestattete IDE mit integrierter Emulator-Unterstützung.

1. Installieren Sie <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Installieren Sie das Flutter-Plugin (Einstellungen -> Plugins -> Flutter)
3. Installieren Sie das Dart-Plugin

Einrichtungsanleitung: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter-Setup</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community oder Ultimate) unterstützt ebenfalls die Flutter-Entwicklung.

1. Installieren Sie IntelliJ IDEA
2. Installieren Sie das Flutter-Plugin (Einstellungen -> Plugins -> Flutter)
3. Installieren Sie das Dart-Plugin

Sobald Ihr Editor konfiguriert ist, sind Sie bereit, [{{ config('app.name') }} zu installieren](/docs/7.x/installation).
