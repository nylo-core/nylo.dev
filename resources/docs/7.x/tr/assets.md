# Varlıklar

---

<a name="section-1"></a>
- [Giriş](#introduction "Varlıklara giriş")
- Dosyalar
  - [Görselleri Görüntüleme](#displaying-images "Görselleri görüntüleme")
  - [Özel Asset Yolları](#custom-asset-paths "Özel asset yolları")
  - [Asset Yollarını Döndürme](#returning-asset-paths "Asset yollarını döndürme")
- Varlıkları Yönetme
  - [Yeni Dosya Ekleme](#adding-new-files "Yeni dosya ekleme")
  - [Asset Yapılandırması](#asset-configuration "Asset yapılandırması")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7, Flutter uygulamanızdaki varlıkları yönetmek için yardımcı metotlar sağlar. Varlıklar `assets/` dizininde saklanır ve görseller, videolar, fontlar ve diğer dosyaları içerir.

Varsayılan asset yapısı:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Görselleri Görüntüleme

Asset'lerden görselleri görüntülemek için `LocalAsset()` widget'ını kullanın:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Her iki metot da yapılandırılmış asset dizinini içeren tam asset yolunu döndürür.

<div id="custom-asset-paths"></div>

## Özel Asset Yolları

Farklı asset alt dizinlerini desteklemek için `LocalAsset` widget'ına özel constructor'lar ekleyebilirsiniz.

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

## Asset Yollarını Döndürme

`assets/` dizinindeki herhangi bir dosya türü için `getAsset()` kullanın:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Çeşitli Widget'larla Kullanım

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Yeni Dosya Ekleme

1. Dosyalarınızı `assets/` dizininin uygun alt dizinine yerleştirin:
   - Görseller: `assets/images/`
   - Videolar: `assets/videos/`
   - Fontlar: `assets/fonts/`
   - Diğer: `assets/data/` veya özel klasörler

2. Klasörün `pubspec.yaml` dosyasında listelenmiş olduğundan emin olun:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Asset Yapılandırması

{{ config('app.name') }} v7, asset yolunu `.env` dosyanızdaki `ASSET_PATH` ortam değişkeni aracılığıyla yapılandırır:

``` bash
ASSET_PATH="assets"
```

Yardımcı fonksiyonlar bu yolu otomatik olarak başa ekler, böylece çağrılarınıza `assets/` eklemeniz gerekmez:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Temel Yolu Değiştirme

Farklı bir asset yapısına ihtiyacınız varsa, `.env` dosyanızdaki `ASSET_PATH` değerini güncelleyin:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Değiştirdikten sonra ortam yapılandırmanızı yeniden oluşturun:

``` bash
metro make:env --force
```

