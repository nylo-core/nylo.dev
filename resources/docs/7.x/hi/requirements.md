# आवश्यकताएँ

---

<a name="section-1"></a>
- [सिस्टम आवश्यकताएँ](#system-requirements "सिस्टम आवश्यकताएँ")
- [Flutter इंस्टॉल करना](#installing-flutter "Flutter इंस्टॉल करना")
- [अपनी इंस्टॉलेशन सत्यापित करना](#verifying-installation "अपनी इंस्टॉलेशन सत्यापित करना")
- [एडिटर सेटअप करें](#set-up-an-editor "एडिटर सेटअप करें")


<div id="system-requirements"></div>

## सिस्टम आवश्यकताएँ

{{ config('app.name') }} v7 के लिए निम्नलिखित न्यूनतम संस्करण आवश्यक हैं:

| आवश्यकता | न्यूनतम संस्करण |
|-------------|-----------------|
| **Flutter** | 3.24.0 या उच्चतर |
| **Dart SDK** | 3.10.7 या उच्चतर |

### प्लेटफ़ॉर्म सपोर्ट

{{ config('app.name') }} उन सभी प्लेटफ़ॉर्म का समर्थन करता है जो Flutter सपोर्ट करता है:

| प्लेटफ़ॉर्म | सपोर्ट |
|----------|---------|
| iOS | ✓ पूर्ण समर्थन |
| Android | ✓ पूर्ण समर्थन |
| Web | ✓ पूर्ण समर्थन |
| macOS | ✓ पूर्ण समर्थन |
| Windows | ✓ पूर्ण समर्थन |
| Linux | ✓ पूर्ण समर्थन |

<div id="installing-flutter"></div>

## Flutter इंस्टॉल करना

यदि आपके पास Flutter इंस्टॉल नहीं है, तो अपने ऑपरेटिंग सिस्टम के लिए आधिकारिक इंस्टॉलेशन गाइड का पालन करें:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter इंस्टॉलेशन गाइड</a>

<div id="verifying-installation"></div>

## अपनी इंस्टॉलेशन सत्यापित करना

Flutter इंस्टॉल करने के बाद, अपना सेटअप सत्यापित करें:

### Flutter संस्करण जाँचें

``` bash
flutter --version
```

आपको इसके समान आउटपुट दिखना चाहिए:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Flutter अपडेट करें (यदि आवश्यक हो)

यदि आपका Flutter संस्करण 3.24.0 से नीचे है, तो नवीनतम स्थिर रिलीज़ में अपग्रेड करें:

``` bash
flutter channel stable
flutter upgrade
```

### Flutter Doctor चलाएँ

सत्यापित करें कि आपका डेवलपमेंट एनवायरनमेंट सही तरीके से कॉन्फ़िगर किया गया है:

``` bash
flutter doctor -v
```

यह कमांड इनकी जाँच करता है:
- Flutter SDK इंस्टॉलेशन
- Android टूलचेन (Android डेवलपमेंट के लिए)
- Xcode (iOS/macOS डेवलपमेंट के लिए)
- कनेक्टेड डिवाइसेज़
- IDE प्लगइन्स

{{ config('app.name') }} इंस्टॉलेशन के साथ आगे बढ़ने से पहले रिपोर्ट की गई किसी भी समस्या को ठीक करें।

<div id="set-up-an-editor"></div>

## एडिटर सेटअप करें

Flutter सपोर्ट वाला IDE चुनें:

### Visual Studio Code (अनुशंसित)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> हल्का है और इसमें उत्कृष्ट Flutter सपोर्ट है।

1. <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> इंस्टॉल करें
2. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter एक्सटेंशन</a> इंस्टॉल करें
3. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart एक्सटेंशन</a> इंस्टॉल करें

सेटअप गाइड: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter सेटअप</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> बिल्ट-इन एमुलेटर सपोर्ट के साथ पूर्ण-विशेषताओं वाला IDE प्रदान करता है।

1. <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> इंस्टॉल करें
2. Flutter प्लगइन इंस्टॉल करें (Preferences → Plugins → Flutter)
3. Dart प्लगइन इंस्टॉल करें

सेटअप गाइड: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter सेटअप</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community या Ultimate) भी Flutter डेवलपमेंट का समर्थन करता है।

1. IntelliJ IDEA इंस्टॉल करें
2. Flutter प्लगइन इंस्टॉल करें (Preferences → Plugins → Flutter)
3. Dart प्लगइन इंस्टॉल करें

एक बार जब आपका एडिटर कॉन्फ़िगर हो जाए, तो आप [{{ config('app.name') }} इंस्टॉल](/docs/7.x/installation) करने के लिए तैयार हैं।
