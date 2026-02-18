# एसेट्स

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- फ़ाइलें
  - [इमेज प्रदर्शित करना](#displaying-images "इमेज प्रदर्शित करना")
  - [कस्टम एसेट पाथ](#custom-asset-paths "कस्टम एसेट पाथ")
  - [एसेट पाथ लौटाना](#returning-asset-paths "एसेट पाथ लौटाना")
- एसेट्स प्रबंधित करना
  - [नई फ़ाइलें जोड़ना](#adding-new-files "नई फ़ाइलें जोड़ना")
  - [एसेट कॉन्फ़िगरेशन](#asset-configuration "एसेट कॉन्फ़िगरेशन")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 आपके Flutter ऐप में एसेट्स प्रबंधित करने के लिए हेल्पर मेथड्स प्रदान करता है। एसेट्स `assets/` डायरेक्टरी में संग्रहीत होते हैं और इसमें इमेज, वीडियो, फ़ॉन्ट और अन्य फ़ाइलें शामिल हैं।

डिफ़ॉल्ट एसेट स्ट्रक्चर:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## इमेज प्रदर्शित करना

एसेट्स से इमेज प्रदर्शित करने के लिए `LocalAsset()` विजेट का उपयोग करें:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

दोनों मेथड्स कॉन्फ़िगर की गई एसेट डायरेक्टरी सहित पूरा एसेट पाथ लौटाते हैं।

<div id="custom-asset-paths"></div>

## कस्टम एसेट पाथ

विभिन्न एसेट सबडायरेक्टरीज़ को सपोर्ट करने के लिए, आप `LocalAsset` विजेट में कस्टम कंस्ट्रक्टर जोड़ सकते हैं।

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

## एसेट पाथ लौटाना

`assets/` डायरेक्टरी में किसी भी फ़ाइल टाइप के लिए `getAsset()` का उपयोग करें:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### विभिन्न विजेट्स के साथ उपयोग

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## नई फ़ाइलें जोड़ना

1. अपनी फ़ाइलें `assets/` की उपयुक्त सबडायरेक्टरी में रखें:
   - इमेज: `assets/images/`
   - वीडियो: `assets/videos/`
   - फ़ॉन्ट: `assets/fonts/`
   - अन्य: `assets/data/` या कस्टम फ़ोल्डर

2. सुनिश्चित करें कि फ़ोल्डर `pubspec.yaml` में सूचीबद्ध है:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## एसेट कॉन्फ़िगरेशन

{{ config('app.name') }} v7 आपकी `.env` फ़ाइल में `ASSET_PATH` एनवायरनमेंट वेरिएबल के माध्यम से एसेट पाथ कॉन्फ़िगर करता है:

``` bash
ASSET_PATH="assets"
```

हेल्पर फ़ंक्शन स्वचालित रूप से इस पाथ को प्रीपेंड करते हैं, इसलिए आपको अपनी कॉल में `assets/` शामिल करने की आवश्यकता नहीं है:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### बेस पाथ बदलना

यदि आपको एक अलग एसेट स्ट्रक्चर की आवश्यकता है, तो अपनी `.env` में `ASSET_PATH` अपडेट करें:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

बदलने के बाद, अपना एनवायरनमेंट कॉन्फ़िग पुनः जेनरेट करें:

``` bash
metro make:env --force
```

