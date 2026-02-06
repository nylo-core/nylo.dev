# Backpack

---

<a name="section-1"></a>
- [परिचय](#introduction "Introduction")
- [बुनियादी उपयोग](#basic-usage "Basic Usage")
- [डेटा पढ़ना](#reading-data "Reading Data")
- [डेटा सहेजना](#saving-data "Saving Data")
- [डेटा हटाना](#deleting-data "Deleting Data")
- [सत्र (Sessions)](#sessions "Sessions")
- [Nylo इंस्टेंस तक पहुँचना](#nylo-instance "Accessing the Nylo Instance")
- [हेल्पर फंक्शन](#helper-functions "Helper Functions")
- [NyStorage के साथ एकीकरण](#integration-with-nystorage "Integration with NyStorage")
- [उदाहरण](#examples "Practical Examples")

<div id="introduction"></div>

## परिचय

**Backpack** {{ config('app.name') }} में एक इन-मेमोरी सिंगलटन स्टोरेज सिस्टम है। यह आपके ऐप के रनटाइम के दौरान डेटा तक तेज़, सिंक्रोनस एक्सेस प्रदान करता है। `NyStorage` के विपरीत जो डेटा को डिवाइस पर स्थायी रूप से सहेजता है, Backpack डेटा को मेमोरी में स्टोर करता है और ऐप बंद होने पर डेटा मिटा दिया जाता है।

Backpack का उपयोग फ्रेमवर्क द्वारा आंतरिक रूप से `Nylo` ऐप ऑब्जेक्ट, `EventBus`, और प्रमाणीकरण डेटा जैसे महत्वपूर्ण इंस्टेंस संग्रहीत करने के लिए किया जाता है। आप इसका उपयोग अपने स्वयं के डेटा को संग्रहीत करने के लिए भी कर सकते हैं जिसे async कॉल के बिना तेज़ी से एक्सेस करने की आवश्यकता है।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
Backpack.instance.save("user_name", "Anthony");

// Read a value (synchronous)
String? name = Backpack.instance.read("user_name");

// Delete a value
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## बुनियादी उपयोग

Backpack **सिंगलटन पैटर्न** का उपयोग करता है -- इसे `Backpack.instance` के माध्यम से एक्सेस करें:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## डेटा पढ़ना

`read<T>()` मेथड का उपयोग करके Backpack से मान पढ़ें। यह जेनेरिक टाइप और एक वैकल्पिक डिफ़ॉल्ट मान का समर्थन करता है:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

जब टाइप प्रदान किया जाता है तो Backpack स्वचालित रूप से JSON स्ट्रिंग को मॉडल ऑब्जेक्ट में डीसीरियलाइज़ करता है:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
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
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
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
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## NyStorage के साथ एकीकरण

Backpack संयुक्त स्थायी + इन-मेमोरी स्टोरेज के लिए `NyStorage` के साथ एकीकृत होता है:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

यह पैटर्न प्रमाणीकरण टोकन जैसे डेटा के लिए उपयोगी है जिसे स्थायित्व और तेज़ सिंक्रोनस एक्सेस दोनों की आवश्यकता होती है (उदा., HTTP इंटरसेप्टर में)।

<div id="examples"></div>

## उदाहरण

### API अनुरोधों के लिए Auth टोकन संग्रहीत करना

``` dart
// In your auth interceptor
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
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### त्वरित फ़ीचर फ़्लैग

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
