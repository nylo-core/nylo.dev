# Assets

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a los recursos")
- Archivos
  - [Mostrar imagenes](#displaying-images "Mostrar imagenes")
  - [Rutas de recursos personalizadas](#custom-asset-paths "Rutas de recursos personalizadas")
  - [Obtener rutas de recursos](#returning-asset-paths "Obtener rutas de recursos")
- Gestion de recursos
  - [Agregar nuevos archivos](#adding-new-files "Agregar nuevos archivos")
  - [Configuracion de recursos](#asset-configuration "Configuracion de recursos")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 proporciona metodos auxiliares para gestionar recursos en tu aplicacion Flutter. Los recursos se almacenan en el directorio `assets/` e incluyen imagenes, videos, fuentes y otros archivos.

La estructura de recursos por defecto:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Mostrar imagenes

Usa el widget `LocalAsset()` para mostrar imagenes desde los recursos:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Ambos metodos devuelven la ruta completa del recurso incluyendo el directorio de recursos configurado.

<div id="custom-asset-paths"></div>

## Rutas de recursos personalizadas

Para soportar diferentes subdirectorios de recursos, puedes agregar constructores personalizados al widget `LocalAsset`.

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

## Obtener rutas de recursos

Usa `getAsset()` para cualquier tipo de archivo en el directorio `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Uso con varios widgets

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Agregar nuevos archivos

1. Coloca tus archivos en el subdirectorio apropiado de `assets/`:
   - Imagenes: `assets/images/`
   - Videos: `assets/videos/`
   - Fuentes: `assets/fonts/`
   - Otros: `assets/data/` o carpetas personalizadas

2. Asegurate de que la carpeta este listada en `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Configuracion de recursos

{{ config('app.name') }} v7 configura la ruta de recursos a traves de la variable de entorno `ASSET_PATH` en tu archivo `.env`:

``` bash
ASSET_PATH="assets"
```

Las funciones auxiliares anteponen automaticamente esta ruta, por lo que no necesitas incluir `assets/` en tus llamadas:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Cambiar la ruta base

Si necesitas una estructura de recursos diferente, actualiza `ASSET_PATH` en tu `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Despues de cambiarla, regenera tu configuracion de entorno:

``` bash
metro make:env --force
```
