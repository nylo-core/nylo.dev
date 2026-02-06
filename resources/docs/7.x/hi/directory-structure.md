# डायरेक्टरी स्ट्रक्चर

---

<a name="section-1"></a>
- [परिचय](#introduction "डायरेक्टरी स्ट्रक्चर का परिचय")
- [रूट डायरेक्टरी](#root-directory "रूट डायरेक्टरी")
- [lib डायरेक्टरी](#lib-directory "lib डायरेक्टरी")
  - [app](#app-directory "App डायरेक्टरी")
  - [bootstrap](#bootstrap-directory "Bootstrap डायरेक्टरी")
  - [config](#config-directory "Config डायरेक्टरी")
  - [resources](#resources-directory "Resources डायरेक्टरी")
  - [routes](#routes-directory "Routes डायरेक्टरी")
- [एसेट्स डायरेक्टरी](#assets-directory "एसेट्स डायरेक्टरी")
- [एसेट हेल्पर्स](#asset-helpers "एसेट हेल्पर्स")


<div id="introduction"></div>

## परिचय

{{ config('app.name') }} <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a> से प्रेरित एक स्वच्छ, व्यवस्थित डायरेक्टरी स्ट्रक्चर का उपयोग करता है। यह स्ट्रक्चर प्रोजेक्ट्स में सुसंगतता बनाए रखने में मदद करता है और फ़ाइलें खोजना आसान बनाता है।

<div id="root-directory"></div>

## रूट डायरेक्टरी

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

## lib डायरेक्टरी

`lib/` फ़ोल्डर में आपका सारा Dart एप्लिकेशन कोड होता है:

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

`app/` डायरेक्टरी में आपके एप्लिकेशन का कोर लॉजिक होता है:

| डायरेक्टरी | उद्देश्य |
|-----------|---------|
| `commands/` | कस्टम Metro CLI कमांड्स |
| `controllers/` | बिज़नेस लॉजिक के लिए पेज कंट्रोलर्स |
| `events/` | इवेंट सिस्टम के लिए इवेंट क्लासेज़ |
| `forms/` | वैलिडेशन के साथ फ़ॉर्म क्लासेज़ |
| `models/` | डेटा मॉडल क्लासेज़ |
| `networking/` | API सर्विसेज़ और नेटवर्क कॉन्फ़िगरेशन |
| `networking/dio/interceptors/` | Dio HTTP इंटरसेप्टर्स |
| `providers/` | ऐप स्टार्ट पर बूट होने वाले सर्विस प्रोवाइडर्स |
| `services/` | सामान्य सर्विस क्लासेज़ |

<div id="bootstrap-directory"></div>

### bootstrap/

`bootstrap/` डायरेक्टरी में वे फ़ाइलें होती हैं जो कॉन्फ़िगर करती हैं कि आपका ऐप कैसे बूट होता है:

| फ़ाइल | उद्देश्य |
|------|---------|
| `boot.dart` | मुख्य बूट सीक्वेंस कॉन्फ़िगरेशन |
| `decoders.dart` | मॉडल और API डीकोडर्स रजिस्ट्रेशन |
| `env.g.dart` | जेनरेटेड एन्क्रिप्टेड एनवायरनमेंट कॉन्फ़िग |
| `events.dart` | इवेंट रजिस्ट्रेशन |
| `extensions.dart` | कस्टम एक्सटेंशन |
| `helpers.dart` | कस्टम हेल्पर फ़ंक्शन |
| `providers.dart` | प्रोवाइडर रजिस्ट्रेशन |
| `theme.dart` | थीम कॉन्फ़िगरेशन |

<div id="config-directory"></div>

### config/

`config/` डायरेक्टरी में एप्लिकेशन कॉन्फ़िगरेशन होता है:

| फ़ाइल | उद्देश्य |
|------|---------|
| `app.dart` | कोर ऐप सेटिंग्स |
| `design.dart` | ऐप डिज़ाइन (फ़ॉन्ट, लोगो, लोडर) |
| `localization.dart` | भाषा और लोकेल सेटिंग्स |
| `storage_keys.dart` | लोकल स्टोरेज की डेफ़िनिशन |
| `toast_notification.dart` | टोस्ट नोटिफ़िकेशन स्टाइल्स |

<div id="resources-directory"></div>

### resources/

`resources/` डायरेक्टरी में UI कंपोनेंट्स होते हैं:

| डायरेक्टरी | उद्देश्य |
|-----------|---------|
| `pages/` | पेज विजेट्स (स्क्रीन्स) |
| `themes/` | थीम डेफ़िनिशन |
| `themes/light/` | लाइट थीम कलर्स |
| `themes/dark/` | डार्क थीम कलर्स |
| `widgets/` | पुन: उपयोग योग्य विजेट कंपोनेंट्स |
| `widgets/buttons/` | कस्टम बटन विजेट्स |
| `widgets/bottom_sheet_modals/` | बॉटम शीट मोडल विजेट्स |

<div id="routes-directory"></div>

### routes/

`routes/` डायरेक्टरी में राउटिंग कॉन्फ़िगरेशन होता है:

| फ़ाइल/डायरेक्टरी | उद्देश्य |
|----------------|---------|
| `router.dart` | रूट डेफ़िनिशन |
| `guards/` | रूट गार्ड क्लासेज़ |

<div id="assets-directory"></div>

## एसेट्स डायरेक्टरी

`assets/` डायरेक्टरी स्टैटिक फ़ाइलें संग्रहीत करती है:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### एसेट्स रजिस्टर करना

एसेट्स `pubspec.yaml` में रजिस्टर किए जाते हैं:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## एसेट हेल्पर्स

{{ config('app.name') }} एसेट्स के साथ काम करने के लिए हेल्पर्स प्रदान करता है।

### इमेज एसेट्स

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

### सामान्य एसेट्स

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### भाषा फ़ाइलें

भाषा फ़ाइलें प्रोजेक्ट रूट पर `lang/` में संग्रहीत होती हैं:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

अधिक विवरण के लिए [लोकलाइज़ेशन](/docs/7.x/localization) देखें।
