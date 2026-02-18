# Aset

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- File
  - [Menampilkan Gambar](#displaying-images "Menampilkan Gambar")
  - [Path Aset Kustom](#custom-asset-paths "Path Aset Kustom")
  - [Mengembalikan Path Aset](#returning-asset-paths "Mengembalikan Path Aset")
- Mengelola Aset
  - [Menambahkan File Baru](#adding-new-files "Menambahkan File Baru")
  - [Konfigurasi Aset](#asset-configuration "Konfigurasi Aset")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyediakan method helper untuk mengelola aset di aplikasi Flutter Anda. Aset disimpan di direktori `assets/` dan mencakup gambar, video, font, dan file lainnya.

Struktur aset default:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Menampilkan Gambar

Gunakan widget `LocalAsset()` untuk menampilkan gambar dari aset:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Kedua method mengembalikan path aset lengkap termasuk direktori aset yang dikonfigurasi.

<div id="custom-asset-paths"></div>

## Path Aset Kustom

Untuk mendukung subdirektori aset yang berbeda, Anda dapat menambahkan konstruktor kustom ke widget `LocalAsset`.

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

## Mengembalikan Path Aset

Gunakan `getAsset()` untuk jenis file apa pun di direktori `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Menggunakan dengan Berbagai Widget

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Menambahkan File Baru

1. Letakkan file Anda di subdirektori yang sesuai dari `assets/`:
   - Gambar: `assets/images/`
   - Video: `assets/videos/`
   - Font: `assets/fonts/`
   - Lainnya: `assets/data/` atau folder kustom

2. Pastikan folder terdaftar di `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Konfigurasi Aset

{{ config('app.name') }} v7 mengkonfigurasi path aset melalui variabel environment `ASSET_PATH` di file `.env` Anda:

``` bash
ASSET_PATH="assets"
```

Fungsi helper secara otomatis menambahkan path ini di depan, sehingga Anda tidak perlu menyertakan `assets/` dalam pemanggilan Anda:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Mengubah Path Dasar

Jika Anda memerlukan struktur aset yang berbeda, perbarui `ASSET_PATH` di `.env` Anda:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Setelah mengubah, regenerasi konfigurasi environment Anda:

``` bash
metro make:env --force
```

