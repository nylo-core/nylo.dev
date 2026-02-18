# Assets

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do zasobow")
- Pliki
  - [Wyswietlanie obrazow](#displaying-images "Wyswietlanie obrazow")
  - [Niestandardowe sciezki zasobow](#custom-asset-paths "Niestandardowe sciezki zasobow")
  - [Zwracanie sciezek zasobow](#returning-asset-paths "Zwracanie sciezek zasobow")
- Zarzadzanie zasobami
  - [Dodawanie nowych plikow](#adding-new-files "Dodawanie nowych plikow")
  - [Konfiguracja zasobow](#asset-configuration "Konfiguracja zasobow")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 udostepnia metody pomocnicze do zarzadzania zasobami w aplikacji Flutter. Zasoby sa przechowywane w katalogu `assets/` i obejmuja obrazy, filmy, czcionki i inne pliki.

Domyslna struktura zasobow:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Wyswietlanie obrazow

Uzyj widgetu `LocalAsset()` do wyswietlania obrazow z zasobow:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Obie metody zwracaja pelna sciezke zasobu, w tym skonfigurowany katalog zasobow.

<div id="custom-asset-paths"></div>

## Niestandardowe sciezki zasobow

Aby obslugiwac rozne podkatalogi zasobow, mozesz dodac niestandardowe konstruktory do widgetu `LocalAsset`.

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- new constructor for icons folder
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// Usage examples
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## Zwracanie sciezek zasobow

Uzyj `getAsset()` dla dowolnego typu pliku w katalogu `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Uzycie z roznymi widgetami

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Dodawanie nowych plikow

1. Umiesc swoje pliki w odpowiednim podkatalogu `assets/`:
   - Obrazy: `assets/images/`
   - Filmy: `assets/videos/`
   - Czcionki: `assets/fonts/`
   - Inne: `assets/data/` lub niestandardowe foldery

2. Upewnij sie, ze folder jest wymieniony w `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Konfiguracja zasobow

{{ config('app.name') }} v7 konfiguruje sciezke zasobow za pomoca zmiennej srodowiskowej `ASSET_PATH` w pliku `.env`:

``` bash
ASSET_PATH="assets"
```

Funkcje pomocnicze automatycznie dopisuja te sciezke, wiec nie musisz dodawac `assets/` w swoich wywolaniach:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Zmiana sciezki bazowej

Jesli potrzebujesz innej struktury zasobow, zaktualizuj `ASSET_PATH` w pliku `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Po zmianie wygeneruj ponownie konfiguracje srodowiska:

``` bash
metro make:env --force
```
