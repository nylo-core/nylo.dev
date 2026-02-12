# लोकलाइज़ेशन

---

<a name="section-1"></a>
- [परिचय](#introduction "लोकलाइज़ेशन का परिचय")
- [कॉन्फ़िगरेशन](#configuration "कॉन्फ़िगरेशन")
- [लोकलाइज़्ड फ़ाइलें जोड़ना](#adding-localized-files "लोकलाइज़्ड फ़ाइलें जोड़ना")
- बेसिक्स
  - [टेक्स्ट लोकलाइज़ करना](#localizing-text "टेक्स्ट लोकलाइज़ करना")
    - [आर्ग्युमेंट्स](#arguments "आर्ग्युमेंट्स")
    - [StyledText प्लेसहोल्डर](#styled-text-placeholders "StyledText प्लेसहोल्डर")
  - [लोकेल अपडेट करना](#updating-the-locale "लोकेल अपडेट करना")
  - [डिफ़ॉल्ट लोकेल सेट करना](#setting-a-default-locale "डिफ़ॉल्ट लोकेल सेट करना")
- एडवांस्ड
  - [सपोर्टेड लोकेल्स](#supported-locales "सपोर्टेड लोकेल्स")
  - [फ़ॉलबैक भाषा](#fallback-language "फ़ॉलबैक भाषा")
  - [RTL सपोर्ट](#rtl-support "RTL सपोर्ट")
  - [मिसिंग कीज़ डीबग करें](#debug-missing-keys "मिसिंग कीज़ डीबग करें")
  - [NyLocalization API](#nylocalization-api "NyLocalization API")
  - [NyLocaleHelper](#nylocalehelper "NyLocaleHelper यूटिलिटी क्लास")
  - [कंट्रोलर से भाषा बदलना](#changing-language-from-controller "कंट्रोलर से भाषा बदलना")


<div id="introduction"></div>

## परिचय

लोकलाइज़ेशन आपको अपने ऐप को कई भाषाओं में प्रदान करने की अनुमति देता है। {{ config('app.name') }} v7 JSON भाषा फ़ाइलों का उपयोग करके टेक्स्ट को लोकलाइज़ करना आसान बनाता है।

यहाँ एक त्वरित उदाहरण है:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**आपके विजेट में:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## कॉन्फ़िगरेशन

लोकलाइज़ेशन `lib/config/localization.dart` में कॉन्फ़िगर किया जाता है:

``` dart
final class LocalizationConfig {
  // Default language code (matches your JSON file, e.g., 'en' for lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - Use device's language setting
  // LocaleType.asDefined - Use languageCode above
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // Directory containing language JSON files
  static const String assetsDirectory = 'lang/';

  // List of supported locales
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // Add more locales as needed
  ];

  // Fallback when a key is not found in the active locale
  static const String fallbackLanguageCode = 'en';

  // RTL language codes
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // Log warnings for missing translation keys
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## लोकलाइज़्ड फ़ाइलें जोड़ना

अपनी भाषा JSON फ़ाइलें `lang/` डायरेक्टरी में जोड़ें:

```
lang/
├── en.json   # English
├── es.json   # Spanish
├── fr.json   # French
└── ...
```

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "settings": "Settings",
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

**lang/es.json**
``` json
{
  "welcome": "Bienvenido",
  "settings": "Configuración",
  "navigation": {
    "home": "Inicio",
    "profile": "Perfil"
  }
}
```

### pubspec.yaml में रजिस्टर करें

सुनिश्चित करें कि आपकी भाषा फ़ाइलें आपकी `pubspec.yaml` में शामिल हैं:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## टेक्स्ट लोकलाइज़ करना

स्ट्रिंग्स को ट्रांसलेट करने के लिए `.tr()` एक्सटेंशन या `trans()` हेल्पर का उपयोग करें:

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### नेस्टेड कीज़

डॉट नोटेशन का उपयोग करके नेस्टेड JSON कीज़ एक्सेस करें:

**lang/en.json**
``` json
{
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

``` dart
"navigation.home".tr()       // "Home"
trans("navigation.profile")  // "Profile"
```

<div id="arguments"></div>

### आर्ग्युमेंट्स

`@{{key}}` सिंटैक्स का उपयोग करके अपने ट्रांसलेशन में डायनामिक वैल्यूज़ पास करें:

**lang/en.json**
``` json
{
  "greeting": "Hello @{{name}}",
  "items_count": "You have @{{count}} items"
}
```

``` dart
"greeting".tr(arguments: {"name": "Anthony"})
// "Hello Anthony"

trans("items_count", arguments: {"count": "5"})
// "You have 5 items"
```

<div id="styled-text-placeholders"></div>

### StyledText प्लेसहोल्डर

जब आप लोकलाइज़्ड स्ट्रिंग्स के साथ `StyledText.template` का उपयोग करते हैं, तो आप `{{key:text}}` सिंटैक्स का उपयोग कर सकते हैं। यह **key** को सभी लोकेल्स में स्थिर रखता है (ताकि आपकी स्टाइल्स और टैप हैंडलर्स हमेशा मैच करें), जबकि **text** प्रति लोकेल अनुवादित होता है।

**lang/hi.json**
``` json
{
  "learn_skills": "{{lang:भाषाएँ}}, {{read:पठन}} और {{speak:बोलना}} सीखें",
  "already_have_account": "पहले से खाता है? {{login:लॉग इन करें}}"
}
```

**lang/es.json**
``` json
{
  "learn_skills": "Aprende {{lang:Idiomas}}, {{read:Lectura}} y {{speak:Habla}}",
  "already_have_account": "¿Ya tienes una cuenta? {{login:Iniciar sesión}}"
}
```

**आपके विजेट में:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

कीज़ `lang`, `read` और `speak` हर लोकेल फ़ाइल में समान हैं, इसलिए स्टाइल मैप सभी भाषाओं के लिए काम करता है। `:` के बाद प्रदर्शित टेक्स्ट वह है जो उपयोगकर्ता देखता है — हिंदी में "भाषाएँ", स्पैनिश में "Idiomas", आदि।

आप इसे `onTap` के साथ भी उपयोग कर सकते हैं:

``` dart
StyledText.template(
  "already_have_account".tr(),
  styles: {
    "login": TextStyle(fontWeight: FontWeight.bold),
  },
  onTap: {
    "login": () => routeTo(LoginPage.path),
  },
)
```

> **नोट:** `@{{key}}` सिंटैक्स (`@` प्रीफ़िक्स के साथ) ट्रांसलेशन समय पर `.tr(arguments:)` द्वारा प्रतिस्थापित आर्ग्युमेंट्स के लिए है। `{{key:text}}` सिंटैक्स (`@` के बिना) रेंडर समय पर पार्स किए जाने वाले `StyledText` प्लेसहोल्डर्स के लिए है। इन्हें मिलाएँ नहीं — डायनामिक वैल्यूज़ के लिए `@{{}}` और स्टाइल्ड स्पैन्स के लिए `{{}}` का उपयोग करें।

<div id="updating-the-locale"></div>

## लोकेल अपडेट करना

रनटाइम पर ऐप की भाषा बदलें:

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

यदि आपका विजेट `NyPage` एक्सटेंड करता है, तो `changeLanguage` हेल्पर का उपयोग करें:

``` dart
class _SettingsPageState extends NyPage<SettingsPage> {

  @override
  Widget view(BuildContext context) {
    return ListView(
      children: [
        ListTile(
          title: Text("English"),
          onTap: () => changeLanguage('en'),
        ),
        ListTile(
          title: Text("Español"),
          onTap: () => changeLanguage('es'),
        ),
      ],
    );
  }
}
```

<div id="setting-a-default-locale"></div>

## डिफ़ॉल्ट लोकेल सेट करना

अपनी `.env` फ़ाइल में डिफ़ॉल्ट भाषा सेट करें:

``` bash
DEFAULT_LOCALE="en"
```

या डिवाइस की लोकेल का उपयोग करने के लिए सेट करें:

``` bash
LOCALE_TYPE="device"
```

`.env` बदलने के बाद, अपना एनवायरनमेंट कॉन्फ़िग रीजनरेट करें:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## सपोर्टेड लोकेल्स

`LocalizationConfig` में डिफ़ाइन करें कि आपका ऐप कौन से लोकेल्स सपोर्ट करता है:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

यह सूची Flutter के `MaterialApp.supportedLocales` द्वारा उपयोग की जाती है।

<div id="fallback-language"></div>

## फ़ॉलबैक भाषा

जब सक्रिय लोकेल में ट्रांसलेशन कुंजी नहीं मिलती, तो {{ config('app.name') }} निर्दिष्ट भाषा पर फ़ॉलबैक करता है:

``` dart
static const String fallbackLanguageCode = 'en';
```

यह सुनिश्चित करता है कि यदि ट्रांसलेशन गायब है तो आपका ऐप कभी भी रॉ कीज़ नहीं दिखाता।

<div id="rtl-support"></div>

## RTL सपोर्ट

{{ config('app.name') }} v7 में राइट-टू-लेफ्ट (RTL) भाषाओं के लिए बिल्ट-इन सपोर्ट शामिल है:

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## मिसिंग कीज़ डीबग करें

डेवलपमेंट के दौरान मिसिंग ट्रांसलेशन कीज़ के लिए चेतावनियाँ सक्षम करें:

अपनी `.env` फ़ाइल में:
``` bash
DEBUG_TRANSLATIONS="true"
```

यह तब चेतावनियाँ लॉग करता है जब `.tr()` कोई कुंजी नहीं खोज पाता, जिससे आपको अनट्रांसलेटेड स्ट्रिंग्स पकड़ने में मदद मिलती है।

<div id="nylocalization-api"></div>

## NyLocalization API

`NyLocalization` एक सिंगलटन है जो सभी लोकलाइज़ेशन प्रबंधित करता है। बेसिक `translate()` मेथड के अलावा, यह कई अतिरिक्त मेथड्स प्रदान करता है:

### जाँचें कि ट्रांसलेशन मौजूद है या नहीं

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### सभी ट्रांसलेशन कीज़ प्राप्त करें

कौन सी कीज़ लोड हैं यह देखने के लिए डीबगिंग में उपयोगी:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### बिना रीस्टार्ट के लोकेल बदलें

यदि आप लोकेल को साइलेंटली बदलना चाहते हैं (ऐप को रीस्टार्ट किए बिना):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

यह नई भाषा फ़ाइल लोड करता है लेकिन ऐप को रीस्टार्ट **नहीं** करता। तब उपयोगी है जब आप UI अपडेट्स को मैन्युअली हैंडल करना चाहते हैं।

### RTL दिशा जाँचें

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### वर्तमान लोकेल एक्सेस करें

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### पूर्ण API रेफरेंस

| मेथड / प्रॉपर्टी | रिटर्न | विवरण |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | सिंगलटन इंस्टेंस |
| `translate(key, [arguments])` | `String` | वैकल्पिक आर्ग्युमेंट्स के साथ कुंजी ट्रांसलेट करें |
| `hasTranslation(key)` | `bool` | जाँचें कि ट्रांसलेशन कुंजी मौजूद है |
| `getAllKeys()` | `List<String>` | सभी लोडेड ट्रांसलेशन कीज़ प्राप्त करें |
| `setLanguage(context, {language, restart})` | `Future<void>` | भाषा बदलें, वैकल्पिक रूप से रीस्टार्ट करें |
| `setLocale({locale})` | `Future<void>` | बिना रीस्टार्ट के लोकेल बदलें |
| `setDebugMissingKeys(enabled)` | `void` | मिसिंग कुंजी लॉगिंग सक्षम/अक्षम करें |
| `isDirectionRTL(context)` | `bool` | जाँचें कि वर्तमान दिशा RTL है |
| `restart(context)` | `void` | ऐप रीस्टार्ट करें |
| `languageCode` | `String` | वर्तमान भाषा कोड |
| `locale` | `Locale` | वर्तमान Locale ऑब्जेक्ट |
| `delegates` | `Iterable<LocalizationsDelegate>` | Flutter लोकलाइज़ेशन डेलीगेट्स |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` लोकेल ऑपरेशन्स के लिए एक स्टैटिक यूटिलिटी क्लास है। यह वर्तमान लोकेल का पता लगाने, RTL सपोर्ट जाँचने और Locale ऑब्जेक्ट्स बनाने के मेथड्स प्रदान करता है।

``` dart
// Get the current system locale
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Get language and country codes
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' or null

// Check if current locale matches
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// RTL detection
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Get text direction
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Create a Locale from strings
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### पूर्ण API रेफरेंस

| मेथड | रिटर्न | विवरण |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | वर्तमान सिस्टम लोकेल प्राप्त करें |
| `getLanguageCode({context})` | `String` | वर्तमान भाषा कोड प्राप्त करें |
| `getCountryCode({context})` | `String?` | वर्तमान देश कोड प्राप्त करें |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | जाँचें कि वर्तमान लोकेल मैच करता है |
| `isRtlLanguage(languageCode)` | `bool` | जाँचें कि भाषा कोड RTL है |
| `isCurrentLocaleRtl({context})` | `bool` | जाँचें कि वर्तमान लोकेल RTL है |
| `getTextDirection(languageCode)` | `TextDirection` | भाषा के लिए TextDirection प्राप्त करें |
| `getCurrentTextDirection({context})` | `TextDirection` | वर्तमान लोकेल के लिए TextDirection प्राप्त करें |
| `toLocale(languageCode, [countryCode])` | `Locale` | स्ट्रिंग्स से Locale बनाएँ |

`rtlLanguages` कॉन्स्टेंट में शामिल हैं: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`।

<div id="changing-language-from-controller"></div>

## कंट्रोलर से भाषा बदलना

यदि आप अपने पेजेज़ के साथ कंट्रोलर्स का उपयोग करते हैं, तो आप `NyController` से भाषा बदल सकते हैं:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es');
  }

  void switchToEnglishNoRestart() {
    changeLanguage('en', restartState: false);
  }
}
```

`restartState` पैरामीटर यह नियंत्रित करता है कि भाषा बदलने के बाद ऐप रीस्टार्ट होता है या नहीं। यदि आप UI अपडेट स्वयं हैंडल करना चाहते हैं तो इसे `false` सेट करें।
