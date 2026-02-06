# Verzeichnisstruktur

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung zur Verzeichnisstruktur")
- [Stammverzeichnis](#root-directory "Stammverzeichnis")
- [Das lib-Verzeichnis](#lib-directory "Das lib-Verzeichnis")
  - [app](#app-directory "App-Verzeichnis")
  - [bootstrap](#bootstrap-directory "Bootstrap-Verzeichnis")
  - [config](#config-directory "Config-Verzeichnis")
  - [resources](#resources-directory "Resources-Verzeichnis")
  - [routes](#routes-directory "Routes-Verzeichnis")
- [Assets-Verzeichnis](#assets-directory "Assets-Verzeichnis")
- [Asset-Helfer](#asset-helpers "Asset-Helfer")


<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} verwendet eine saubere, organisierte Verzeichnisstruktur, inspiriert von <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Diese Struktur hilft, Konsistenz über Projekte hinweg zu wahren und macht es einfach, Dateien zu finden.

<div id="root-directory"></div>

## Stammverzeichnis

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

## Das lib-Verzeichnis

Der `lib/`-Ordner enthält Ihren gesamten Dart-Anwendungscode:

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

Das Verzeichnis `app/` enthält die Kernlogik Ihrer Anwendung:

| Verzeichnis | Zweck |
|-------------|-------|
| `commands/` | Benutzerdefinierte Metro-CLI-Befehle |
| `controllers/` | Seiten-Controller für Geschäftslogik |
| `events/` | Event-Klassen für das Event-System |
| `forms/` | Formularklassen mit Validierung |
| `models/` | Datenmodell-Klassen |
| `networking/` | API-Services und Netzwerkkonfiguration |
| `networking/dio/interceptors/` | Dio-HTTP-Interceptors |
| `providers/` | Service-Provider, die beim App-Start gebootet werden |
| `services/` | Allgemeine Service-Klassen |

<div id="bootstrap-directory"></div>

### bootstrap/

Das Verzeichnis `bootstrap/` enthält Dateien, die konfigurieren, wie Ihre App bootet:

| Datei | Zweck |
|-------|-------|
| `boot.dart` | Konfiguration der Haupt-Boot-Sequenz |
| `decoders.dart` | Registrierung von Model- und API-Decoders |
| `env.g.dart` | Generierte verschlüsselte Umgebungskonfiguration |
| `events.dart` | Event-Registrierung |
| `extensions.dart` | Benutzerdefinierte Erweiterungen |
| `helpers.dart` | Benutzerdefinierte Hilfsfunktionen |
| `providers.dart` | Provider-Registrierung |
| `theme.dart` | Theme-Konfiguration |

<div id="config-directory"></div>

### config/

Das Verzeichnis `config/` enthält die Anwendungskonfiguration:

| Datei | Zweck |
|-------|-------|
| `app.dart` | Kern-App-Einstellungen |
| `design.dart` | App-Design (Schriftart, Logo, Ladeanimation) |
| `localization.dart` | Sprach- und Locale-Einstellungen |
| `storage_keys.dart` | Definitionen für lokale Speicherschlüssel |
| `toast_notification.dart` | Toast-Benachrichtigungsstile |

<div id="resources-directory"></div>

### resources/

Das Verzeichnis `resources/` enthält UI-Komponenten:

| Verzeichnis | Zweck |
|-------------|-------|
| `pages/` | Seiten-Widgets (Bildschirme) |
| `themes/` | Theme-Definitionen |
| `themes/light/` | Helles Theme - Farben |
| `themes/dark/` | Dunkles Theme - Farben |
| `widgets/` | Wiederverwendbare Widget-Komponenten |
| `widgets/buttons/` | Benutzerdefinierte Button-Widgets |
| `widgets/bottom_sheet_modals/` | Bottom-Sheet-Modal-Widgets |

<div id="routes-directory"></div>

### routes/

Das Verzeichnis `routes/` enthält die Routing-Konfiguration:

| Datei/Verzeichnis | Zweck |
|-------------------|-------|
| `router.dart` | Routendefinitionen |
| `guards/` | Route-Guard-Klassen |

<div id="assets-directory"></div>

## Assets-Verzeichnis

Das Verzeichnis `assets/` speichert statische Dateien:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Assets registrieren

Assets werden in der `pubspec.yaml` registriert:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Asset-Helfer

{{ config('app.name') }} bietet Helfer für die Arbeit mit Assets.

### Bild-Assets

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

### Allgemeine Assets

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Sprachdateien

Sprachdateien werden im Verzeichnis `lang/` im Projektstamm gespeichert:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Siehe [Lokalisierung](/docs/7.x/localization) für weitere Details.
