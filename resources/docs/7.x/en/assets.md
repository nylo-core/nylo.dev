# Assets

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to assets")
- Files
  - [Displaying Images](#displaying-images "Displaying images")
  - [Custom Asset Paths](#custom-asset-paths "Custom asset paths")
  - [Returning Asset Paths](#returning-asset-paths "Returning asset paths")
- Managing Assets
  - [Adding New Files](#adding-new-files "Adding new files")
  - [Asset Configuration](#asset-configuration "Asset configuration")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 provides helper methods for managing assets in your Flutter app. Assets are stored in the `assets/` directory and include images, videos, fonts, and other files.

The default asset structure:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Displaying Images

Use the `LocalAsset()` widget to display images from the assets:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Both methods return the full asset path including the configured asset directory.

<div id="custom-asset-paths"></div>

## Custom Asset Paths

To support different asset subdirectories, you can add custom constructors to the `LocalAsset` widget.

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

## Returning Asset Paths

Use `getAsset()` for any file type in the `assets/` directory:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Using with Various Widgets

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Adding New Files

1. Place your files in the appropriate subdirectory of `assets/`:
   - Images: `assets/images/`
   - Videos: `assets/videos/`
   - Fonts: `assets/fonts/`
   - Other: `assets/data/` or custom folders

2. Ensure the folder is listed in `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Asset Configuration

{{ config('app.name') }} v7 configures the asset path via the `ASSET_PATH` environment variable in your `.env` file:

``` bash
ASSET_PATH="assets"
```

The helper functions automatically prepend this path, so you don't need to include `assets/` in your calls:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Changing the Base Path

If you need a different asset structure, update `ASSET_PATH` in your `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

After changing, regenerate your environment config:

``` bash
metro make:env --force
```

