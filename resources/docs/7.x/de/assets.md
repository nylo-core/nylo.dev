# Assets

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Dateien
  - [Bilder anzeigen](#displaying-images "Bilder anzeigen")
  - [Benutzerdefinierte Asset-Pfade](#custom-asset-paths "Benutzerdefinierte Asset-Pfade")
  - [Asset-Pfade zurückgeben](#returning-asset-paths "Asset-Pfade zurückgeben")
- Assets verwalten
  - [Neue Dateien hinzufügen](#adding-new-files "Neue Dateien hinzufügen")
  - [Asset-Konfiguration](#asset-configuration "Asset-Konfiguration")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 bietet Hilfsmethoden zur Verwaltung von Assets in Ihrer Flutter-App. Assets werden im Verzeichnis `assets/` gespeichert und umfassen Bilder, Videos, Schriftarten und andere Dateien.

Die Standard-Asset-Struktur:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Bilder anzeigen

Verwenden Sie das `LocalAsset()`-Widget, um Bilder aus den Assets anzuzeigen:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Beide Methoden geben den vollständigen Asset-Pfad einschließlich des konfigurierten Asset-Verzeichnisses zurück.

<div id="custom-asset-paths"></div>

## Benutzerdefinierte Asset-Pfade

Um verschiedene Asset-Unterverzeichnisse zu unterstützen, können Sie benutzerdefinierte Konstruktoren zum `LocalAsset`-Widget hinzufügen.

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

## Asset-Pfade zurückgeben

Verwenden Sie `getAsset()` für jeden Dateityp im Verzeichnis `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Verwendung mit verschiedenen Widgets

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Neue Dateien hinzufügen

1. Platzieren Sie Ihre Dateien im entsprechenden Unterverzeichnis von `assets/`:
   - Bilder: `assets/images/`
   - Videos: `assets/videos/`
   - Schriftarten: `assets/fonts/`
   - Sonstiges: `assets/data/` oder benutzerdefinierte Ordner

2. Stellen Sie sicher, dass der Ordner in der `pubspec.yaml` aufgeführt ist:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Asset-Konfiguration

{{ config('app.name') }} v7 konfiguriert den Asset-Pfad über die Umgebungsvariable `ASSET_PATH` in Ihrer `.env`-Datei:

``` bash
ASSET_PATH="assets"
```

Die Hilfsfunktionen fügen diesen Pfad automatisch voran, sodass Sie `assets/` nicht in Ihren Aufrufen angeben müssen:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Basispfad ändern

Wenn Sie eine andere Asset-Struktur benötigen, aktualisieren Sie `ASSET_PATH` in Ihrer `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Generieren Sie nach der Änderung Ihre Umgebungskonfiguration neu:

``` bash
metro make:env --force
```

