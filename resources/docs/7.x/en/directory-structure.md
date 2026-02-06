# Directory Structure

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to directory structure")
- [Root Directory](#root-directory "Root directory")
- [The lib Directory](#lib-directory "The lib directory")
  - [app](#app-directory "App directory")
  - [bootstrap](#bootstrap-directory "Bootstrap directory")
  - [config](#config-directory "Config directory")
  - [resources](#resources-directory "Resources directory")
  - [routes](#routes-directory "Routes directory")
- [Assets Directory](#assets-directory "Assets directory")
- [Asset Helpers](#asset-helpers "Asset helpers")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} uses a clean, organized directory structure inspired by <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. This structure helps maintain consistency across projects and makes it easy to find files.

<div id="root-directory"></div>

## Root Directory

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

## The lib Directory

The `lib/` folder contains all your Dart application code:

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

The `app/` directory contains your application's core logic:

| Directory | Purpose |
|-----------|---------|
| `commands/` | Custom Metro CLI commands |
| `controllers/` | Page controllers for business logic |
| `events/` | Event classes for the event system |
| `forms/` | Form classes with validation |
| `models/` | Data model classes |
| `networking/` | API services and network configuration |
| `networking/dio/interceptors/` | Dio HTTP interceptors |
| `providers/` | Service providers booted at app start |
| `services/` | General service classes |

<div id="bootstrap-directory"></div>

### bootstrap/

The `bootstrap/` directory contains files that configure how your app boots:

| File | Purpose |
|------|---------|
| `boot.dart` | Main boot sequence configuration |
| `decoders.dart` | Model and API decoders registration |
| `env.g.dart` | Generated encrypted environment config |
| `events.dart` | Event registration |
| `extensions.dart` | Custom extensions |
| `helpers.dart` | Custom helper functions |
| `providers.dart` | Provider registration |
| `theme.dart` | Theme configuration |

<div id="config-directory"></div>

### config/

The `config/` directory contains application configuration:

| File | Purpose |
|------|---------|
| `app.dart` | Core app settings |
| `design.dart` | App design (font, logo, loader) |
| `localization.dart` | Language and locale settings |
| `storage_keys.dart` | Local storage key definitions |
| `toast_notification.dart` | Toast notification styles |

<div id="resources-directory"></div>

### resources/

The `resources/` directory contains UI components:

| Directory | Purpose |
|-----------|---------|
| `pages/` | Page widgets (screens) |
| `themes/` | Theme definitions |
| `themes/light/` | Light theme colors |
| `themes/dark/` | Dark theme colors |
| `widgets/` | Reusable widget components |
| `widgets/buttons/` | Custom button widgets |
| `widgets/bottom_sheet_modals/` | Bottom sheet modal widgets |

<div id="routes-directory"></div>

### routes/

The `routes/` directory contains routing configuration:

| File/Directory | Purpose |
|----------------|---------|
| `router.dart` | Route definitions |
| `guards/` | Route guard classes |

<div id="assets-directory"></div>

## Assets Directory

The `assets/` directory stores static files:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Registering Assets

Assets are registered in `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Asset Helpers

{{ config('app.name') }} provides helpers for working with assets.

### Image Assets

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

### General Assets

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Language Files

Language files are stored in `lang/` at the project root:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

See [Localization](/docs/7.x/localization) for more details.
