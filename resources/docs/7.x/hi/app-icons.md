# ऐप आइकन

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [ऐप आइकन जेनरेट करना](#generating-app-icons "ऐप आइकन जेनरेट करना")
- [अपना ऐप आइकन जोड़ना](#adding-your-app-icon "अपना ऐप आइकन जोड़ना")
- [ऐप आइकन आवश्यकताएँ](#app-icon-requirements "ऐप आइकन आवश्यकताएँ")
- [कॉन्फ़िगरेशन](#configuration "कॉन्फ़िगरेशन")
- [बैज काउंट](#badge-count "बैज काउंट")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 एक सिंगल सोर्स इमेज से iOS और Android के लिए ऐप आइकन जेनरेट करने के लिए <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> का उपयोग करता है।

आपका ऐप आइकन **1024x1024 पिक्सेल** के आकार के साथ `assets/app_icon/` डायरेक्टरी में रखा जाना चाहिए।

<div id="generating-app-icons"></div>

## ऐप आइकन जेनरेट करना

सभी प्लेटफ़ॉर्म के लिए आइकन जेनरेट करने के लिए निम्नलिखित कमांड चलाएँ:

``` bash
dart run flutter_launcher_icons
```

यह `assets/app_icon/` से आपका सोर्स आइकन पढ़ता है और जेनरेट करता है:
- `ios/Runner/Assets.xcassets/AppIcon.appiconset/` में iOS आइकन
- `android/app/src/main/res/mipmap-*/` में Android आइकन

<div id="adding-your-app-icon"></div>

## अपना ऐप आइकन जोड़ना

1. अपना आइकन **1024x1024 PNG** फ़ाइल के रूप में बनाएँ
2. इसे `assets/app_icon/` में रखें (जैसे, `assets/app_icon/icon.png`)
3. यदि आवश्यक हो तो अपने `pubspec.yaml` में `image_path` अपडेट करें:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. आइकन जेनरेशन कमांड चलाएँ

<div id="app-icon-requirements"></div>

## ऐप आइकन आवश्यकताएँ

| गुण | मान |
|-----------|-------|
| फ़ॉर्मेट | PNG |
| आकार | 1024x1024 पिक्सेल |
| लेयर्स | बिना ट्रांसपेरेंसी के फ्लैटन किया हुआ |

### फ़ाइल नामकरण

विशेष कैरेक्टर्स के बिना सरल फ़ाइलनाम रखें:
- `app_icon.png`
- `icon.png`

### प्लेटफ़ॉर्म दिशानिर्देश

विस्तृत आवश्यकताओं के लिए, आधिकारिक प्लेटफ़ॉर्म दिशानिर्देश देखें:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## कॉन्फ़िगरेशन

अपने `pubspec.yaml` में आइकन जेनरेशन कस्टमाइज़ करें:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

सभी उपलब्ध विकल्पों के लिए <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons डॉक्यूमेंटेशन</a> देखें।

<div id="badge-count"></div>

## बैज काउंट

{{ config('app.name') }} ऐप बैज काउंट (ऐप आइकन पर दिखाई देने वाली संख्या) प्रबंधित करने के लिए हेल्पर फ़ंक्शन प्रदान करता है:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### प्लेटफ़ॉर्म सपोर्ट

बैज काउंट इन पर समर्थित हैं:
- **iOS**: नेटिव सपोर्ट
- **Android**: लॉन्चर सपोर्ट आवश्यक (अधिकांश लॉन्चर इसका समर्थन करते हैं)
- **Web**: समर्थित नहीं

### उपयोग के मामले

बैज काउंट के लिए सामान्य परिदृश्य:
- अपठित नोटिफ़िकेशन
- लंबित संदेश
- कार्ट में आइटम
- अधूरे कार्य

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```

