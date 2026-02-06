# Risorse

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione alle risorse")
- File
  - [Visualizzare Immagini](#displaying-images "Visualizzare immagini")
  - [Percorsi Risorse Personalizzati](#custom-asset-paths "Percorsi risorse personalizzati")
  - [Restituire Percorsi delle Risorse](#returning-asset-paths "Restituire percorsi delle risorse")
- Gestione Risorse
  - [Aggiungere Nuovi File](#adding-new-files "Aggiungere nuovi file")
  - [Configurazione delle Risorse](#asset-configuration "Configurazione delle risorse")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 fornisce metodi helper per gestire le risorse nella tua app Flutter. Le risorse sono memorizzate nella directory `assets/` e includono immagini, video, font e altri file.

La struttura predefinita delle risorse:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Visualizzare Immagini

Usa il widget `LocalAsset()` per visualizzare immagini dagli asset:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Entrambi i metodi restituiscono il percorso completo della risorsa includendo la directory delle risorse configurata.

<div id="custom-asset-paths"></div>

## Percorsi Risorse Personalizzati

Per supportare diverse sottodirectory di risorse, puoi aggiungere costruttori personalizzati al widget `LocalAsset`.

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

## Restituire Percorsi delle Risorse

Usa `getAsset()` per qualsiasi tipo di file nella directory `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Utilizzo con Vari Widget

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Aggiungere Nuovi File

1. Posiziona i tuoi file nella sottodirectory appropriata di `assets/`:
   - Immagini: `assets/images/`
   - Video: `assets/videos/`
   - Font: `assets/fonts/`
   - Altro: `assets/data/` o cartelle personalizzate

2. Assicurati che la cartella sia elencata in `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Configurazione delle Risorse

{{ config('app.name') }} v7 configura il percorso delle risorse tramite la variabile d'ambiente `ASSET_PATH` nel tuo file `.env`:

``` bash
ASSET_PATH="assets"
```

Le funzioni helper antepongono automaticamente questo percorso, quindi non hai bisogno di includere `assets/` nelle tue chiamate:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Cambiare il Percorso Base

Se hai bisogno di una struttura di risorse diversa, aggiorna `ASSET_PATH` nel tuo `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Dopo la modifica, rigenera la tua configurazione dell'ambiente:

``` bash
metro make:env --force
```

