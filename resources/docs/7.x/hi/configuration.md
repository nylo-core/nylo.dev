# कॉन्फ़िगरेशन

---

<a name="section-1"></a>
- [परिचय](#introduction "कॉन्फ़िगरेशन का परिचय")
- एनवायरनमेंट
  - [.env फ़ाइल](#env-file ".env फ़ाइल")
  - [एनवायरनमेंट कॉन्फ़िग जेनरेट करना](#generating-env "एनवायरनमेंट कॉन्फ़िग जेनरेट करना")
  - [वैल्यूज़ प्राप्त करना](#retrieving-values "एनवायरनमेंट वैल्यूज़ प्राप्त करना")
  - [कॉन्फ़िग क्लासेज़ बनाना](#creating-config-classes "कॉन्फ़िग क्लासेज़ बनाना")
  - [वेरिएबल टाइप्स](#variable-types "एनवायरनमेंट वेरिएबल टाइप्स")
- [एनवायरनमेंट फ्लेवर्स](#environment-flavours "एनवायरनमेंट फ्लेवर्स")
- [बिल्ड-टाइम इंजेक्शन](#build-time-injection "बिल्ड-टाइम इंजेक्शन")


<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 एक सुरक्षित एनवायरनमेंट कॉन्फ़िगरेशन सिस्टम का उपयोग करता है। आपके एनवायरनमेंट वेरिएबल्स एक `.env` फ़ाइल में संग्रहीत होते हैं और फिर आपके ऐप में उपयोग के लिए एक जेनरेटेड Dart फ़ाइल (`env.g.dart`) में एन्क्रिप्ट किए जाते हैं।

यह दृष्टिकोण प्रदान करता है:
- **सुरक्षा**: एनवायरनमेंट वैल्यूज़ कम्पाइल्ड ऐप में XOR-एन्क्रिप्टेड होती हैं
- **टाइप सेफ्टी**: वैल्यूज़ स्वचालित रूप से उपयुक्त टाइप्स में पार्स होती हैं
- **बिल्ड-टाइम लचीलापन**: डेवलपमेंट, स्टेजिंग और प्रोडक्शन के लिए अलग-अलग कॉन्फ़िगरेशन

<div id="env-file"></div>

## .env फ़ाइल

आपके प्रोजेक्ट रूट पर `.env` फ़ाइल में आपके कॉन्फ़िगरेशन वेरिएबल्स होते हैं:

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### उपलब्ध वेरिएबल्स

| वेरिएबल | विवरण |
|----------|-------------|
| `APP_KEY` | **आवश्यक**। एन्क्रिप्शन के लिए 32-कैरेक्टर सीक्रेट की |
| `APP_NAME` | आपके एप्लिकेशन का नाम |
| `APP_ENV` | एनवायरनमेंट: `developing` या `production` |
| `APP_DEBUG` | डिबग मोड सक्षम करें (`true`/`false`) |
| `APP_URL` | आपके ऐप का URL |
| `API_BASE_URL` | API अनुरोधों के लिए बेस URL |
| `ASSET_PATH` | एसेट्स फ़ोल्डर का पाथ |
| `DEFAULT_LOCALE` | डिफ़ॉल्ट भाषा कोड |

<div id="generating-env"></div>

## एनवायरनमेंट कॉन्फ़िग जेनरेट करना

{{ config('app.name') }} v7 के लिए आपको अपने ऐप के env वैल्यूज़ तक पहुँचने से पहले एक एन्क्रिप्टेड एनवायरनमेंट फ़ाइल जेनरेट करनी होगी।

### चरण 1: APP_KEY जेनरेट करें

सबसे पहले, एक सुरक्षित APP_KEY जेनरेट करें:

``` bash
metro make:key
```

यह आपकी `.env` फ़ाइल में एक 32-कैरेक्टर `APP_KEY` जोड़ता है।

### चरण 2: env.g.dart जेनरेट करें

एन्क्रिप्टेड एनवायरनमेंट फ़ाइल जेनरेट करें:

``` bash
metro make:env
```

यह आपके एन्क्रिप्टेड एनवायरनमेंट वेरिएबल्स के साथ `lib/bootstrap/env.g.dart` बनाता है।

जब आपका ऐप शुरू होता है तब आपका env स्वचालित रूप से पंजीकृत हो जाता है — `main.dart` में `Nylo.init(env: Env.get, ...)` यह आपके लिए हैंडल करता है। कोई अतिरिक्त सेटअप की आवश्यकता नहीं है।

### बदलावों के बाद पुनः जेनरेट करना

जब आप अपनी `.env` फ़ाइल को संशोधित करें, तो कॉन्फ़िग को पुनः जेनरेट करें:

``` bash
metro make:env --force
```

`--force` फ्लैग मौजूदा `env.g.dart` को ओवरराइट करता है।

<div id="retrieving-values"></div>

## वैल्यूज़ प्राप्त करना

एनवायरनमेंट वैल्यूज़ तक पहुँचने का अनुशंसित तरीका **config क्लासेज़** के माध्यम से है। आपकी `lib/config/app.dart` फ़ाइल env वैल्यूज़ को टाइप्ड स्टैटिक फ़ील्ड्स के रूप में एक्सपोज़ करने के लिए `getEnv()` का उपयोग करती है:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

फिर अपने ऐप कोड में, config क्लास के माध्यम से वैल्यूज़ एक्सेस करें:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

यह पैटर्न env एक्सेस को आपकी config क्लासेज़ में केंद्रीकृत रखता है। `getEnv()` हेल्पर का उपयोग सीधे ऐप कोड में करने के बजाय config क्लासेज़ के अंदर किया जाना चाहिए।

<div id="creating-config-classes"></div>

## कॉन्फ़िग क्लासेज़ बनाना

आप Metro का उपयोग करके थर्ड-पार्टी सेवाओं या फ़ीचर-विशिष्ट कॉन्फ़िगरेशन के लिए कस्टम config क्लासेज़ बना सकते हैं:

``` bash
metro make:config RevenueCat
```

यह `lib/config/revenue_cat_config.dart` पर एक नई config फ़ाइल बनाता है:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### उदाहरण: RevenueCat कॉन्फ़िगरेशन

**चरण 1:** अपनी `.env` फ़ाइल में एनवायरनमेंट वेरिएबल्स जोड़ें:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**चरण 2:** इन वैल्यूज़ को रेफ़रेंस करने के लिए अपनी config क्लास अपडेट करें:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**चरण 3:** अपनी एनवायरनमेंट कॉन्फ़िग पुनः जेनरेट करें:

``` bash
metro make:env --force
```

**चरण 4:** अपने ऐप में config क्लास का उपयोग करें:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

यह दृष्टिकोण आपकी API कीज़ और कॉन्फ़िगरेशन वैल्यूज़ को सुरक्षित और केंद्रीकृत रखता है, जिससे विभिन्न एनवायरनमेंट्स में अलग-अलग वैल्यूज़ प्रबंधित करना आसान हो जाता है।

<div id="variable-types"></div>

## वेरिएबल टाइप्स

आपकी `.env` फ़ाइल में वैल्यूज़ स्वचालित रूप से पार्स होती हैं:

| .env वैल्यू | Dart टाइप | उदाहरण |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (खाली स्ट्रिंग) |


<div id="environment-flavours"></div>

## एनवायरनमेंट फ्लेवर्स

डेवलपमेंट, स्टेजिंग और प्रोडक्शन के लिए अलग-अलग कॉन्फ़िगरेशन बनाएँ।

### चरण 1: एनवायरनमेंट फ़ाइलें बनाएँ

अलग-अलग `.env` फ़ाइलें बनाएँ:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

`.env.production` का उदाहरण:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### चरण 2: एनवायरनमेंट कॉन्फ़िग जेनरेट करें

किसी विशिष्ट env फ़ाइल से जेनरेट करें:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### चरण 3: अपना ऐप बिल्ड करें

उपयुक्त कॉन्फ़िगरेशन के साथ बिल्ड करें:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## बिल्ड-टाइम इंजेक्शन

बेहतर सुरक्षा के लिए, आप APP_KEY को सोर्स कोड में एम्बेड करने के बजाय बिल्ड टाइम पर इंजेक्ट कर सकते हैं।

### --dart-define मोड के साथ जेनरेट करें

``` bash
metro make:env --dart-define
```

यह APP_KEY को एम्बेड किए बिना `env.g.dart` जेनरेट करता है।

### APP_KEY इंजेक्शन के साथ बिल्ड करें

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

यह दृष्टिकोण APP_KEY को आपके सोर्स कोड से बाहर रखता है, जो इनके लिए उपयोगी है:
- CI/CD पाइपलाइन जहाँ सीक्रेट्स इंजेक्ट किए जाते हैं
- ओपन सोर्स प्रोजेक्ट्स
- बेहतर सुरक्षा आवश्यकताएँ

### सर्वोत्तम प्रथाएँ

1. **कभी भी `.env` को वर्शन कंट्रोल में कमिट न करें** - इसे `.gitignore` में जोड़ें
2. **`.env-example` का उपयोग करें** - संवेदनशील वैल्यूज़ के बिना एक टेम्पलेट कमिट करें
3. **बदलावों के बाद पुनः जेनरेट करें** - `.env` को संशोधित करने के बाद हमेशा `metro make:env --force` चलाएँ
4. **प्रति एनवायरनमेंट अलग कीज़** - डेवलपमेंट, स्टेजिंग और प्रोडक्शन के लिए यूनिक APP_KEY उपयोग करें
