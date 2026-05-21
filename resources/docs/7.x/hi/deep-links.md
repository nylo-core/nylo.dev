# डीप लिंक्स

---

<a name="section-1"></a>

- [परिचय](#introduction "परिचय")
- [शुरुआत करें](#getting-started "शुरुआत करें")
  - [डीप लिंक्स सक्षम करें](#enable-deep-links "डीप लिंक्स सक्षम करें")
  - [प्रोवाइडर जेनरेट करें](#generate-a-provider "प्रोवाइडर जेनरेट करें")
- [प्लेटफ़ॉर्म कॉन्फ़िगरेशन](#platform-configuration "प्लेटफ़ॉर्म कॉन्फ़िगरेशन")
  - [Android कॉन्फ़िगरेशन](#android-configuration "Android कॉन्फ़िगरेशन")
  - [iOS कॉन्फ़िगरेशन](#ios-configuration "iOS कॉन्फ़िगरेशन")
- [लिंक्स इंटरसेप्ट करना](#intercepting-links "लिंक्स इंटरसेप्ट करना")
- [फ़ॉलबैक रूट](#fallback-route "फ़ॉलबैक रूट")
- [परीक्षण](#testing "परीक्षण")
- [API रेफ़रेंस](#api-reference "API रेफ़रेंस")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} में [`app_links`](https://pub.dev/packages/app_links) द्वारा संचालित फर्स्ट-क्लास डीप लिंक सपोर्ट शामिल है। Android App Links, iOS Universal Links, कस्टम URL स्कीम और वेब URLs से आने वाले URIs स्वचालित रूप से कैप्चर होते हैं और आपके रजिस्टर्ड रूट्स के माध्यम से रूट किए जाते हैं।

एक ही लाइन पूरी पाइपलाइन सक्षम करती है:

```dart
nylo.useDeepLinks();
```

जब कोई URI आता है, तो {{ config('app.name') }} पाथ और क्वेरी पैरामीटर निकालता है और उनके साथ `routeTo()` कॉल करता है। कोल्ड-स्टार्ट लॉन्च (किसी लिंक द्वारा ऐप खुलना) नेविगेटर तैयार होने के बाद रिप्ले होते हैं, और वार्म-स्टार्ट लिंक्स (ऐप चलते समय प्राप्त) उसी तरह हैंडल होते हैं।

<div id="getting-started"></div>

## शुरुआत करें

<div id="enable-deep-links"></div>

### डीप लिंक्स सक्षम करें

किसी भी प्रोवाइडर के `setup` मेथड के अंदर `useDeepLinks()` कॉल करें:

```dart
import 'package:nylo_framework/nylo_framework.dart';

class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

प्रोवाइडर को `config/providers.dart` में रजिस्टर करें ताकि यह ऐप बूट के दौरान चले।

<div id="generate-a-provider"></div>

### प्रोवाइडर जेनरेट करें

Metro आपके लिए प्रोवाइडर स्कैफोल्ड करता है:

```bash
dart run nylo_framework:main make:deep_link_provider
```

यह `lib/app/providers/deep_link_provider.dart` बनाता है जिसमें `useDeepLinks()` पहले से वायर्ड है और एक कमेंटेड `onIncomingLink` उदाहरण है। प्रोवाइडर को `config/providers.dart` में भी स्वचालित रूप से जोड़ा जाता है।

<div id="platform-configuration"></div>

## प्लेटफ़ॉर्म कॉन्फ़िगरेशन

<div id="android-configuration"></div>

### Android कॉन्फ़िगरेशन

`android/app/src/main/AndroidManifest.xml` में अपनी मुख्य `<activity>` में `<intent-filter>` जोड़ें। नीचे दिया उदाहरण Android App Links (`https://example.com/...`) और कस्टम स्कीम (`myapp://...`) दोनों स्वीकार करता है:

```xml
<activity
    android:name=".MainActivity"
    android:exported="true"
    android:launchMode="singleTop">

    <!-- Standard Flutter intent-filter -->
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>

    <!-- Android App Links -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="https"
              android:host="example.com" />
    </intent-filter>

    <!-- Custom URL scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="myapp" />
    </intent-filter>
</activity>
```

वेरिफ़ाइड App Links के लिए, `https://example.com/.well-known/assetlinks.json` पर `assetlinks.json` फ़ाइल होस्ट करें। पूरी जानकारी के लिए <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">Flutter App Links गाइड</a> देखें।

<div id="ios-configuration"></div>

### iOS कॉन्फ़िगरेशन

**कस्टम URL स्कीम** के लिए, `ios/Runner/Info.plist` में `CFBundleURLTypes` एंट्री जोड़ें:

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.example.myapp</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>myapp</string>
        </array>
    </dict>
</array>
```

**Universal Links** के लिए, Xcode में Associated Domains क्षमता सक्षम करें और अपने डोमेन पर `apple-app-site-association` फ़ाइल होस्ट करें। पूरी जानकारी के लिए <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">Flutter Universal Links गाइड</a> देखें।

<div id="intercepting-links"></div>

## लिंक्स इंटरसेप्ट करना

{{ config('app.name') }} द्वारा URI रूट करने से पहले लॉजिक चलाने के लिए `onIncomingLink` का उपयोग करें। {{ config('app.name') }} को स्वचालित रूप से रूट करने देने के लिए `true` रिटर्न करें, या URI को स्वयं हैंडल करने के लिए `false` रिटर्न करें:

```dart
class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();

    nylo.onIncomingLink((Uri uri) async {
      // Require auth on /account/* routes
      if (uri.path.startsWith('/account') && !(await Auth.isAuthenticated())) {
        routeTo(LoginPage.path);
        return false;
      }

      // Track analytics for every incoming link
      Analytics.track('deep_link_opened', {'path': uri.path});

      return true;
    });

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

कॉलबैक को पूरा `Uri` मिलता है, जिसमें स्कीम, होस्ट, पाथ और क्वेरी पैरामीटर शामिल हैं।

<div id="fallback-route"></div>

## फ़ॉलबैक रूट

जब आने वाले URI का पाथ रजिस्टर्ड नहीं हो तो किसी उचित जगह पर जाने के लिए `useDeepLinks()` में `fallbackRoute` पास करें:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

जब URI का पाथ किसी रजिस्टर्ड रूट से मेल खाता है, तो {{ config('app.name') }} उस पर रूट करता है। अन्यथा, यूज़र फ़ॉलबैक पर पहुँचता है।

<div id="testing"></div>

## परीक्षण

कमांड लाइन से आने वाले डीप लिंक्स सिमुलेट करें:

```bash
# Android — custom scheme
adb shell am start -W -a android.intent.action.VIEW \
    -d "myapp://user/42?ref=test" com.example.myapp

# Android — App Link
adb shell am start -W -a android.intent.action.VIEW \
    -d "https://example.com/user/42?ref=test" com.example.myapp

# iOS Simulator
xcrun simctl openurl booted "myapp://user/42?ref=test"
```

कोल्ड-स्टार्ट व्यवहार जाँचने के लिए टेस्ट के बीच ऐप बंद करें, और वार्म-स्टार्ट जाँचने के लिए ऐप फोरग्राउंड में होते हुए URL ट्रिगर करें।

<div id="api-reference"></div>

## API रेफ़रेंस

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

प्लेटफ़ॉर्म डीप-लिंक कैप्चर सक्षम करता है। इसे प्रोवाइडर के `setup` मेथड के अंदर कॉल करें।

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | जब आने वाले URI का पाथ रजिस्टर्ड न हो तब नेविगेट करने का रूट। |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

प्रत्येक आने वाले URI के लिए एक कॉलबैक रजिस्टर करता है। {{ config('app.name') }} को स्वचालित रूप से रूट करने देने के लिए `true` रिटर्न करें, या स्वचालित रूटिंग रोकने के लिए `false` रिटर्न करें।

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | आने वाला URI प्राप्त करता है। |
