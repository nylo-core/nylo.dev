# Backpack

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बुनियादी उपयोग](#basic-usage "बुनियादी उपयोग")
- [डेटा पढ़ना](#reading-data "डेटा पढ़ना")
- [डेटा सहेजना](#saving-data "डेटा सहेजना")
- [डेटा हटाना](#deleting-data "डेटा हटाना")
- [सत्र (Sessions)](#sessions "सत्र (Sessions)")
- [Nylo इंस्टेंस तक पहुँचना](#nylo-instance "Nylo इंस्टेंस तक पहुँचना")
- [हेल्पर फंक्शन](#helper-functions "हेल्पर फंक्शन")
- [NyStorage के साथ एकीकरण](#integration-with-nystorage "NyStorage के साथ एकीकरण")
- [उदाहरण](#examples "उदाहरण")

<div id="introduction"></div>

## परिचय

**Backpack** {{ config('app.name') }} में एक इन-मेमोरी सिंगलटन स्टोरेज सिस्टम है। यह आपके ऐप के रनटाइम के दौरान डेटा तक तेज़, सिंक्रोनस एक्सेस प्रदान करता है। `NyStorage` के विपरीत जो डेटा को डिवाइस पर स्थायी रूप से सहेजता है, Backpack डेटा को मेमोरी में स्टोर करता है और ऐप बंद होने पर डेटा मिटा दिया जाता है।

Backpack का उपयोग फ्रेमवर्क द्वारा आंतरिक रूप से `Nylo` ऐप ऑब्जेक्ट, `EventBus`, और प्रमाणीकरण डेटा जैसे महत्वपूर्ण इंस्टेंस संग्रहीत करने के लिए किया जाता है। आप इसका उपयोग अपने स्वयं के डेटा को संग्रहीत करने के लिए भी कर सकते हैं जिसे async कॉल के बिना तेज़ी से एक्सेस करने की आवश्यकता है।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// मान सहेजें
Backpack.instance.save("user_name", "Anthony");

// मान पढ़ें (synchronous)
String? name = Backpack.instance.read("user_name");

// मान हटाएँ
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## बुनियादी उपयोग

Backpack **सिंगलटन पैटर्न** का उपयोग करता है -- इसे `Backpack.instance` के माध्यम से एक्सेस करें:

``` dart
// डेटा सहेजें
Backpack.instance.save("theme", "dark");

// डेटा पढ़ें
String? theme = Backpack.instance.read("theme"); // "dark"

// जाँचें कि डेटा मौजूद है या नहीं
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## डेटा पढ़ना

`read<T>()` मेथड का उपयोग करके Backpack से मान पढ़ें। यह जेनेरिक टाइप और एक वैकल्पिक डिफ़ॉल्ट मान का समर्थन करता है:

``` dart
// एक String पढ़ें
String? name = Backpack.instance.read<String>("name");

// डिफ़ॉल्ट मान के साथ पढ़ें
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// एक int पढ़ें
int? score = Backpack.instance.read<int>("score");
```

जब टाइप प्रदान किया जाता है तो Backpack स्वचालित रूप से संग्रहीत मानों को मॉडल ऑब्जेक्ट में डीसीरियलाइज़ करता है। यह JSON स्ट्रिंग और raw `Map<String, dynamic>` दोनों के लिए काम करता है:

``` dart
// यदि User मॉडल JSON स्ट्रिंग के रूप में संग्रहीत है, तो डीसीरियलाइज़ होगा
User? user = Backpack.instance.read<User>("current_user");

// यदि raw Map संग्रहीत है (उदा. NyStorage से syncKeys के माध्यम से), तो यह भी
// पढ़ते समय स्वचालित रूप से टाइप किए गए मॉडल में डीसीरियलाइज़ होता है
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // एक User लौटाता है
```

<div id="saving-data"></div>

## डेटा सहेजना

`save()` मेथड का उपयोग करके मान सहेजें:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### डेटा जोड़ना (Append)

किसी कुंजी पर संग्रहीत सूची में मान जोड़ने के लिए `append()` का उपयोग करें:

``` dart
// सूची में जोड़ें
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// सीमा के साथ जोड़ें (केवल अंतिम N आइटम रखता है)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## डेटा हटाना

### एक कुंजी हटाना

``` dart
Backpack.instance.delete("api_token");
```

### सभी डेटा हटाना

`deleteAll()` मेथड आरक्षित फ्रेमवर्क कुंजियों (`nylo` और `event_bus`) **को छोड़कर** सभी मान हटा देता है:

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## सत्र (Sessions)

Backpack डेटा को नामित समूहों में व्यवस्थित करने के लिए सत्र प्रबंधन प्रदान करता है। यह संबंधित डेटा को एक साथ संग्रहीत करने के लिए उपयोगी है।

### सत्र मान अपडेट करें

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### सत्र मान प्राप्त करें

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### सत्र कुंजी हटाएँ

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### पूरा सत्र फ्लश करें

``` dart
Backpack.instance.sessionFlush("cart");
```

### सभी सत्र डेटा प्राप्त करें

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Nylo इंस्टेंस तक पहुँचना

Backpack `Nylo` एप्लिकेशन इंस्टेंस को संग्रहीत करता है। आप इसे इस प्रकार प्राप्त कर सकते हैं:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

जाँचें कि Nylo इंस्टेंस इनिशियलाइज़ हुआ है या नहीं:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## हेल्पर फंक्शन

{{ config('app.name') }} सामान्य Backpack ऑपरेशन के लिए ग्लोबल हेल्पर फंक्शन प्रदान करता है:

| फंक्शन | विवरण |
|----------|-------------|
| `backpackRead<T>(key)` | Backpack से मान पढ़ें |
| `backpackSave(key, value)` | Backpack में मान सहेजें |
| `backpackDelete(key)` | Backpack से मान हटाएँ |
| `backpackDeleteAll()` | सभी मान हटाएँ (फ्रेमवर्क कुंजियाँ सुरक्षित रहती हैं) |
| `backpackNylo()` | Backpack से Nylo इंस्टेंस प्राप्त करें |

### उदाहरण

``` dart
// हेल्पर फंक्शन का उपयोग करके
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Nylo इंस्टेंस एक्सेस करें
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## NyStorage के साथ एकीकरण

Backpack संयुक्त स्थायी + इन-मेमोरी स्टोरेज के लिए `NyStorage` के साथ एकीकृत होता है:

``` dart
// NyStorage (स्थायी) और Backpack (इन-मेमोरी) दोनों में सहेजें
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// अब Backpack के माध्यम से synchronous रूप से उपलब्ध
String? token = Backpack.instance.read("auth_token");

// NyStorage से हटाते समय, Backpack से भी साफ़ करें
await NyStorage.deleteAll(andFromBackpack: true);
```

यह पैटर्न प्रमाणीकरण टोकन जैसे डेटा के लिए उपयोगी है जिसे स्थायित्व और तेज़ सिंक्रोनस एक्सेस दोनों की आवश्यकता होती है (उदा., HTTP इंटरसेप्टर में)।

<div id="examples"></div>

## उदाहरण

### API अनुरोधों के लिए Auth टोकन संग्रहीत करना

``` dart
// अपने auth interceptor में
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### सत्र-आधारित कार्ट प्रबंधन

``` dart
// कार्ट सत्र में आइटम जोड़ें
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// कार्ट डेटा पढ़ें
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// कार्ट साफ़ करें
Backpack.instance.sessionFlush("cart");
```

### त्वरित फ़ीचर फ़्लैग

``` dart
// तेज़ एक्सेस के लिए Backpack में फ़ीचर फ़्लैग संग्रहीत करें
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// फ़ीचर फ़्लैग जाँचें
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
