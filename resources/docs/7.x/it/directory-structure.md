# Struttura delle Directory

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Directory Radice](#root-directory "Directory Radice")
- [La Directory lib](#lib-directory "La Directory lib")
  - [app](#app-directory "app")
  - [bootstrap](#bootstrap-directory "bootstrap")
  - [config](#config-directory "config")
  - [resources](#resources-directory "resources")
  - [routes](#routes-directory "routes")
- [Directory Assets](#assets-directory "Directory Assets")
- [Helper per gli Asset](#asset-helpers "Helper per gli Asset")


<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} utilizza una struttura di directory pulita e organizzata ispirata a <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Questa struttura aiuta a mantenere la coerenza tra i progetti e rende facile trovare i file.

<div id="root-directory"></div>

## Directory Radice

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

## La Directory lib

La cartella `lib/` contiene tutto il codice Dart della tua applicazione:

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

La directory `app/` contiene la logica core della tua applicazione:

| Directory | Scopo |
|-----------|-------|
| `commands/` | Comandi CLI Metro personalizzati |
| `controllers/` | Controller delle pagine per la logica di business |
| `events/` | Classi evento per il sistema di eventi |
| `forms/` | Classi form con validazione |
| `models/` | Classi modello dei dati |
| `networking/` | Servizi API e configurazione di rete |
| `networking/dio/interceptors/` | Interceptor HTTP Dio |
| `providers/` | Provider di servizio avviati all'avvio dell'app |
| `services/` | Classi di servizio generali |

<div id="bootstrap-directory"></div>

### bootstrap/

La directory `bootstrap/` contiene i file che configurano l'avvio della tua app:

| File | Scopo |
|------|-------|
| `boot.dart` | Configurazione della sequenza di avvio principale |
| `decoders.dart` | Registrazione dei decoder per modelli e API |
| `env.g.dart` | Configurazione ambiente crittografata generata |
| `events.dart` | Registrazione degli eventi |
| `extensions.dart` | Estensioni personalizzate |
| `helpers.dart` | Funzioni helper personalizzate |
| `providers.dart` | Registrazione dei provider |
| `theme.dart` | Configurazione del tema |

<div id="config-directory"></div>

### config/

La directory `config/` contiene la configurazione dell'applicazione:

| File | Scopo |
|------|-------|
| `app.dart` | Impostazioni core dell'app |
| `design.dart` | Design dell'app (font, logo, loader) |
| `localization.dart` | Impostazioni lingua e localizzazione |
| `storage_keys.dart` | Definizioni delle chiavi di storage locale |
| `toast_notification.dart` | Stili delle notifiche toast |

<div id="resources-directory"></div>

### resources/

La directory `resources/` contiene i componenti UI:

| Directory | Scopo |
|-----------|-------|
| `pages/` | Widget pagina (schermate) |
| `themes/` | Definizioni dei temi |
| `themes/light/` | Colori del tema chiaro |
| `themes/dark/` | Colori del tema scuro |
| `widgets/` | Componenti widget riutilizzabili |
| `widgets/buttons/` | Widget pulsante personalizzati |
| `widgets/bottom_sheet_modals/` | Widget modal bottom sheet |

<div id="routes-directory"></div>

### routes/

La directory `routes/` contiene la configurazione del routing:

| File/Directory | Scopo |
|----------------|-------|
| `router.dart` | Definizioni delle rotte |
| `guards/` | Classi route guard |

<div id="assets-directory"></div>

## Directory Assets

La directory `assets/` memorizza i file statici:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Registrazione degli Asset

Gli asset vengono registrati nel `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Helper per gli Asset

{{ config('app.name') }} fornisce helper per lavorare con gli asset.

### Asset Immagine

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

### Asset Generali

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### File di Lingua

I file di lingua sono memorizzati in `lang/` nella radice del progetto:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Vedi [Localizzazione](/docs/7.x/localization) per maggiori dettagli.
