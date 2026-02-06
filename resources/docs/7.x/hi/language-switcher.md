# LanguageSwitcher

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- उपयोग
    - [ड्रॉपडाउन विजेट](#usage-dropdown "ड्रॉपडाउन विजेट")
    - [बॉटम शीट मोडल](#usage-bottom-modal "बॉटम शीट मोडल")
- [कस्टम ड्रॉपडाउन बिल्डर](#custom-builder "कस्टम ड्रॉपडाउन बिल्डर")
- [पैरामीटर्स](#parameters "पैरामीटर्स")
- [स्टैटिक मेथड्स](#methods "स्टैटिक मेथड्स")


<div id="introduction"></div>

## परिचय

**LanguageSwitcher** विजेट आपके {{ config('app.name') }} प्रोजेक्ट्स में भाषा बदलने को हैंडल करने का एक आसान तरीका प्रदान करता है। यह स्वचालित रूप से आपकी `/lang` डायरेक्टरी में उपलब्ध भाषाओं का पता लगाता है और उन्हें यूज़र को दिखाता है।

**LanguageSwitcher क्या करता है?**

- आपकी `/lang` डायरेक्टरी से उपलब्ध भाषाएँ दिखाता है
- जब यूज़र कोई भाषा चुनता है तो ऐप की भाषा बदल देता है
- ऐप रीस्टार्ट होने पर भी चुनी हुई भाषा बनी रहती है
- भाषा बदलने पर UI स्वचालित रूप से अपडेट होता है

> **नोट**: यदि आपका ऐप अभी तक लोकलाइज़्ड नहीं है, तो इस विजेट का उपयोग करने से पहले [Localization](/docs/7.x/localization) डॉक्यूमेंटेशन में जानें कि यह कैसे करें।

<div id="usage-dropdown"></div>

## ड्रॉपडाउन विजेट

`LanguageSwitcher` का उपयोग करने का सबसे सरल तरीका आपके ऐप बार में ड्रॉपडाउन के रूप में है:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Add to the app bar
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

जब यूज़र ड्रॉपडाउन पर टैप करता है, तो उसे उपलब्ध भाषाओं की एक लिस्ट दिखाई देगी। भाषा चुनने के बाद, ऐप स्वचालित रूप से बदल जाएगा और UI अपडेट हो जाएगा।

<div id="usage-bottom-modal"></div>

## बॉटम शीट मोडल

आप भाषाओं को बॉटम शीट मोडल में भी दिखा सकते हैं:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

बॉटम मोडल वर्तमान में चुनी गई भाषा के बगल में चेकमार्क के साथ भाषाओं की एक लिस्ट दिखाता है।

### मोडल की ऊँचाई कस्टमाइज़ करना

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## कस्टम ड्रॉपडाउन बिल्डर

ड्रॉपडाउन में प्रत्येक भाषा विकल्प कैसे दिखाई देता है इसे कस्टमाइज़ करें:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
      ],
    );
  },
)
```

### भाषा बदलाव हैंडल करना

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## पैरामीटर्स

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | ड्रॉपडाउन बटन के लिए कस्टम आइकन |
| `iconEnabledColor` | `Color?` | - | ड्रॉपडाउन आइकन का रंग |
| `iconSize` | `double` | `24` | ड्रॉपडाउन आइकन का आकार |
| `dropdownBgColor` | `Color?` | - | ड्रॉपडाउन मेनू का बैकग्राउंड रंग |
| `hint` | `Widget?` | - | कोई भाषा न चुनी होने पर हिंट विजेट |
| `itemHeight` | `double` | `kMinInteractiveDimension` | प्रत्येक ड्रॉपडाउन आइटम की ऊँचाई |
| `elevation` | `int` | `8` | ड्रॉपडाउन मेनू का एलिवेशन |
| `padding` | `EdgeInsetsGeometry?` | - | ड्रॉपडाउन के चारों ओर पैडिंग |
| `borderRadius` | `BorderRadius?` | - | ड्रॉपडाउन मेनू का बॉर्डर रेडियस |
| `textStyle` | `TextStyle?` | - | ड्रॉपडाउन आइटम्स के लिए टेक्स्ट स्टाइल |
| `langPath` | `String` | `'lang'` | एसेट्स में भाषा फ़ाइलों का पथ |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | ड्रॉपडाउन आइटम्स के लिए कस्टम बिल्डर |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | ड्रॉपडाउन आइटम्स का अलाइनमेंट |
| `dropdownOnTap` | `Function()?` | - | ड्रॉपडाउन आइटम पर टैप करने पर कॉलबैक |
| `onTap` | `Function()?` | - | ड्रॉपडाउन बटन पर टैप करने पर कॉलबैक |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | भाषा बदलने पर कॉलबैक |

<div id="methods"></div>

## स्टैटिक मेथड्स

### वर्तमान भाषा प्राप्त करें

वर्तमान में चुनी गई भाषा प्राप्त करें:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### भाषा स्टोर करें

मैन्युअली भाषा प्राथमिकता स्टोर करें:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### भाषा क्लियर करें

स्टोर की गई भाषा प्राथमिकता हटाएँ:

``` dart
await LanguageSwitcher.clearLanguage();
```

### भाषा डेटा प्राप्त करें

लोकेल कोड से भाषा जानकारी प्राप्त करें:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### भाषा सूची प्राप्त करें

`/lang` डायरेक्टरी से सभी उपलब्ध भाषाएँ प्राप्त करें:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### बॉटम मोडल दिखाएँ

भाषा चयन मोडल दिखाएँ:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## समर्थित लोकेल्स

`LanguageSwitcher` विजेट मानव-पठनीय नामों के साथ सैकड़ों लोकेल कोड्स को सपोर्ट करता है। कुछ उदाहरण:

| लोकेल कोड | भाषा का नाम |
|-------------|---------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

पूर्ण सूची में अधिकांश भाषाओं के क्षेत्रीय वेरिएंट्स शामिल हैं।
