# कैश

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- बेसिक्स
  - [एक्सपायरेशन के साथ सेव करें](#save-with-expiration "एक्सपायरेशन के साथ सेव करें")
  - [हमेशा के लिए सेव करें](#save-forever "हमेशा के लिए सेव करें")
  - [डेटा प्राप्त करें](#retrieve-data "डेटा प्राप्त करें")
  - [डेटा सीधे स्टोर करें](#store-data-directly "डेटा सीधे स्टोर करें")
  - [डेटा हटाएँ](#remove-data "डेटा हटाएँ")
  - [कैश जाँचें](#check-cache "कैश जाँचें")
- नेटवर्किंग
  - [API रिस्पॉन्स कैश करना](#caching-api-responses "API रिस्पॉन्स कैश करना")
- [प्लेटफ़ॉर्म सपोर्ट](#platform-support "प्लेटफ़ॉर्म सपोर्ट")
- [API रेफरेंस](#api-reference "API रेफरेंस")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 डेटा को कुशलता से संग्रहीत और पुनर्प्राप्त करने के लिए एक फ़ाइल-आधारित कैश सिस्टम प्रदान करता है। कैशिंग महंगे डेटा जैसे API रिस्पॉन्स या कंप्यूटेड रिजल्ट्स को संग्रहीत करने के लिए उपयोगी है।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Cache a value for 60 seconds
String? value = await cache().saveRemember("my_key", 60, () {
  return "Hello World";
});

// Retrieve the cached value
String? cached = await cache().get("my_key");

// Remove from cache
await cache().clear("my_key");
```

<div id="save-with-expiration"></div>

## एक्सपायरेशन के साथ सेव करें

एक्सपायरेशन टाइम के साथ वैल्यू कैश करने के लिए `saveRemember` का उपयोग करें:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

एक्सपायरेशन विंडो के भीतर बाद की कॉल पर, कॉलबैक निष्पादित किए बिना कैश्ड वैल्यू रिटर्न होती है।

<div id="save-forever"></div>

## हमेशा के लिए सेव करें

डेटा को अनिश्चित काल के लिए कैश करने के लिए `saveForever` का उपयोग करें:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

डेटा तब तक कैश्ड रहता है जब तक इसे स्पष्ट रूप से हटाया न जाए या ऐप का कैश क्लियर न हो।

<div id="retrieve-data"></div>

## डेटा प्राप्त करें

कैश्ड वैल्यू सीधे प्राप्त करें:

``` dart
// Retrieve cached value
String? value = await cache().get<String>("my_key");

// With type casting
Map<String, dynamic>? data = await cache().get<Map<String, dynamic>>("user_data");

// Returns null if not found or expired
if (value == null) {
  print("Cache miss or expired");
}
```

यदि कैश्ड आइटम एक्सपायर हो गया है, तो `get()` स्वचालित रूप से इसे हटा देता है और `null` रिटर्न करता है।

<div id="store-data-directly"></div>

## डेटा सीधे स्टोर करें

कॉलबैक के बिना सीधे वैल्यू स्टोर करने के लिए `put` का उपयोग करें:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## डेटा हटाएँ

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## कैश जाँचें

``` dart
// Check if a key exists
bool exists = await cache().has("my_key");

// Get all cached keys
List<String> keys = await cache().documents();

// Get total cache size in bytes
int sizeInBytes = await cache().size();
print("Cache size: ${sizeInBytes / 1024} KB");
```

<div id="caching-api-responses"></div>

## API रिस्पॉन्स कैश करना

### api() हेल्पर का उपयोग करना

API रिस्पॉन्स को सीधे कैश करें:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### NyApiService का उपयोग करना

अपने API सर्विस मेथड्स में कैशिंग डिफ़ाइन करें:

``` dart
class ApiService extends NyApiService {

  Future<Map<String, dynamic>?> getRepoInfo() async {
    return await network(
      request: (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
      cacheKey: "github_repo_info",
      cacheDuration: const Duration(hours: 1),
    );
  }

  Future<List<User>?> getUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
      cacheKey: "users_list",
      cacheDuration: const Duration(minutes: 10),
    );
  }
}
```

फिर मेथड कॉल करें:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## प्लेटफ़ॉर्म सपोर्ट

{{ config('app.name') }} का कैश फ़ाइल-आधारित स्टोरेज का उपयोग करता है और इसका प्लेटफ़ॉर्म सपोर्ट निम्नलिखित है:

| प्लेटफ़ॉर्म | सपोर्ट |
|----------|---------|
| iOS | पूर्ण सपोर्ट |
| Android | पूर्ण सपोर्ट |
| macOS | पूर्ण सपोर्ट |
| Windows | पूर्ण सपोर्ट |
| Linux | पूर्ण सपोर्ट |
| Web | उपलब्ध नहीं |

वेब प्लेटफ़ॉर्म पर, कैश ग्रेसफुली डिग्रेड होता है - कॉलबैक हमेशा निष्पादित होते हैं और कैशिंग बायपास होती है।

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## API रेफरेंस

### मेथड्स

| मेथड | विवरण |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | एक्सपायरेशन के साथ वैल्यू कैश करें। कैश्ड वैल्यू या कॉलबैक रिजल्ट रिटर्न करता है। |
| `saveForever<T>(key, callback)` | अनिश्चित काल के लिए वैल्यू कैश करें। कैश्ड वैल्यू या कॉलबैक रिजल्ट रिटर्न करता है। |
| `get<T>(key)` | कैश्ड वैल्यू प्राप्त करें। न मिलने या एक्सपायर होने पर `null` रिटर्न करता है। |
| `put<T>(key, value, {seconds})` | सीधे वैल्यू स्टोर करें। सेकंड में वैकल्पिक एक्सपायरेशन। |
| `clear(key)` | विशिष्ट कैश्ड आइटम हटाएँ। |
| `flush()` | सभी कैश्ड आइटम हटाएँ। |
| `has(key)` | जाँचें कि कोई की कैश में मौजूद है। `bool` रिटर्न करता है। |
| `documents()` | सभी कैश कीज़ की सूची प्राप्त करें। `List<String>` रिटर्न करता है। |
| `size()` | बाइट्स में कुल कैश साइज़ प्राप्त करें। `int` रिटर्न करता है। |

### प्रॉपर्टीज़

| प्रॉपर्टी | टाइप | विवरण |
|----------|------|-------------|
| `isAvailable` | `bool` | वर्तमान प्लेटफ़ॉर्म पर कैशिंग उपलब्ध है या नहीं। |

