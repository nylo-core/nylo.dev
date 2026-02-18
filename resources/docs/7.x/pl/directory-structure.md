# Directory Structure

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do struktury katalogow")
- [Katalog glowny](#root-directory "Katalog glowny")
- [Katalog lib](#lib-directory "Katalog lib")
  - [app](#app-directory "Katalog app")
  - [bootstrap](#bootstrap-directory "Katalog bootstrap")
  - [config](#config-directory "Katalog config")
  - [resources](#resources-directory "Katalog resources")
  - [routes](#routes-directory "Katalog routes")
- [Katalog zasobow](#assets-directory "Katalog zasobow")
- [Helpery zasobow](#asset-helpers "Helpery zasobow")


<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} uzywa czystej, zorganizowanej struktury katalogow inspirowanej <a href="https://github.com/laravel/laravel" target="_BLANK">Laravelem</a>. Ta struktura pomaga utrzymac spojnosc miedzy projektami i ulatwia znajdowanie plikow.

<div id="root-directory"></div>

## Katalog glowny

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

## Katalog lib

Folder `lib/` zawiera caly kod Dart aplikacji:

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

Katalog `app/` zawiera glowna logike aplikacji:

| Katalog | Cel |
|---------|-----|
| `commands/` | Niestandardowe komendy Metro CLI |
| `controllers/` | Kontrolery stron dla logiki biznesowej |
| `events/` | Klasy zdarzen dla systemu zdarzen |
| `forms/` | Klasy formularzy z walidacja |
| `models/` | Klasy modeli danych |
| `networking/` | Serwisy API i konfiguracja sieci |
| `networking/dio/interceptors/` | Interceptory HTTP Dio |
| `providers/` | Providery uslug uruchamiane przy starcie aplikacji |
| `services/` | Ogolne klasy uslug |

<div id="bootstrap-directory"></div>

### bootstrap/

Katalog `bootstrap/` zawiera pliki konfigurujace sposob uruchamiania aplikacji:

| Plik | Cel |
|------|-----|
| `boot.dart` | Konfiguracja glownej sekwencji uruchamiania |
| `decoders.dart` | Rejestracja dekoderow modeli i API |
| `env.g.dart` | Wygenerowana zaszyfrowana konfiguracja srodowiskowa |
| `events.dart` | Rejestracja zdarzen |
| `extensions.dart` | Niestandardowe rozszerzenia |
| `helpers.dart` | Niestandardowe funkcje pomocnicze |
| `providers.dart` | Rejestracja providerow |
| `theme.dart` | Konfiguracja motywu |

<div id="config-directory"></div>

### config/

Katalog `config/` zawiera konfiguracje aplikacji:

| Plik | Cel |
|------|-----|
| `app.dart` | Glowne ustawienia aplikacji |
| `design.dart` | Design aplikacji (czcionka, logo, loader) |
| `localization.dart` | Ustawienia jezyka i lokalizacji |
| `storage_keys.dart` | Definicje kluczy lokalnego przechowywania |
| `toast_notification.dart` | Style powiadomien toast |

<div id="resources-directory"></div>

### resources/

Katalog `resources/` zawiera komponenty UI:

| Katalog | Cel |
|---------|-----|
| `pages/` | Widgety stron (ekrany) |
| `themes/` | Definicje motywow |
| `themes/light/` | Kolory jasnego motywu |
| `themes/dark/` | Kolory ciemnego motywu |
| `widgets/` | Wielokrotnie uzywane komponenty widgetow |
| `widgets/buttons/` | Niestandardowe widgety przyciskow |
| `widgets/bottom_sheet_modals/` | Widgety modali dolnego arkusza |

<div id="routes-directory"></div>

### routes/

Katalog `routes/` zawiera konfiguracje routingu:

| Plik/Katalog | Cel |
|--------------|-----|
| `router.dart` | Definicje tras |
| `guards/` | Klasy straznikow tras |

<div id="assets-directory"></div>

## Katalog zasobow

Katalog `assets/` przechowuje pliki statyczne:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Rejestrowanie zasobow

Zasoby sa rejestrowane w `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Helpery zasobow

{{ config('app.name') }} udostepnia helpery do pracy z zasobami.

### Zasoby graficzne

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

### Zasoby ogolne

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Pliki jezykowe

Pliki jezykowe sa przechowywane w `lang/` w katalogu glownym projektu:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Zobacz [Lokalizacja](/docs/7.x/localization), aby uzyskac wiecej szczegolow.
