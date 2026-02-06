# लॉगिंग

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [लॉग लेवल्स](#log-levels "लॉग लेवल्स")
- [लॉग मेथड्स](#log-methods "लॉग मेथड्स")
- [JSON लॉगिंग](#json-logging "JSON लॉगिंग")
- [रंगीन आउटपुट](#colored-output "रंगीन आउटपुट")
- [लॉग लिसनर्स](#log-listeners "लॉग लिसनर्स")
- [हेल्पर एक्सटेंशन्स](#helper-extensions "हेल्पर एक्सटेंशन्स")
- [कॉन्फ़िगरेशन](#configuration "कॉन्फ़िगरेशन")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 एक व्यापक लॉगिंग सिस्टम प्रदान करता है।

लॉग्स केवल तभी प्रिंट होते हैं जब आपकी `.env` फ़ाइल में `APP_DEBUG=true` हो, जिससे प्रोडक्शन ऐप्स साफ़ रहते हैं।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## लॉग लेवल्स

{{ config('app.name') }} v7 रंगीन आउटपुट के साथ कई लॉग लेवल्स को सपोर्ट करता है:

| लेवल | मेथड | रंग | उपयोग |
|-------|--------|-------|----------|
| Debug | `printDebug()` | Cyan | विस्तृत डीबगिंग जानकारी |
| Info | `printInfo()` | Blue | सामान्य जानकारी |
| Error | `printError()` | Red | एरर्स और एक्सेप्शन्स |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

आउटपुट उदाहरण:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## लॉग मेथड्स

### बेसिक लॉगिंग

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### स्टैक ट्रेस के साथ एरर

बेहतर डीबगिंग के लिए स्टैक ट्रेस के साथ एरर्स लॉग करें:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### डीबग मोड की परवाह किए बिना फ़ोर्स प्रिंट

`APP_DEBUG=false` होने पर भी प्रिंट करने के लिए `alwaysPrint: true` का उपयोग करें:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### अगला लॉग दिखाएँ (एक बार का ओवरराइड)

`APP_DEBUG=false` होने पर एक बार लॉग प्रिंट करें:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## JSON लॉगिंग

{{ config('app.name') }} v7 में एक समर्पित JSON लॉगिंग मेथड शामिल है:

``` dart
Map<String, dynamic> userData = {
  "id": 123,
  "name": "Anthony",
  "email": "anthony@example.com"
};

// Compact JSON
printJson(userData);
// {"id":123,"name":"Anthony","email":"anthony@example.com"}

// Pretty printed JSON
printJson(userData, prettyPrint: true);
// {
//   "id": 123,
//   "name": "Anthony",
//   "email": "anthony@example.com"
// }
```

<div id="colored-output"></div>

## रंगीन आउटपुट

{{ config('app.name') }} v7 डीबग मोड में लॉग आउटपुट के लिए ANSI रंगों का उपयोग करता है। प्रत्येक लॉग लेवल में आसान पहचान के लिए एक विशिष्ट रंग होता है।

### रंग अक्षम करें

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

रंग स्वचालित रूप से अक्षम हो जाते हैं:
- रिलीज़ मोड में
- जब टर्मिनल ANSI एस्केप कोड्स को सपोर्ट नहीं करता

<div id="log-listeners"></div>

## लॉग लिसनर्स

{{ config('app.name') }} v7 आपको रियल-टाइम में सभी लॉग एंट्रीज़ सुनने की अनुमति देता है:

``` dart
// Set up a log listener
NyLogger.onLog = (NyLogEntry entry) {
  print("Log: [${entry.type}] ${entry.message}");

  // Send to crash reporting service
  if (entry.type == 'error') {
    CrashReporter.log(entry.message, stackTrace: entry.stackTrace);
  }
};
```

### NyLogEntry प्रॉपर्टीज़

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### उपयोग के मामले

- क्रैश रिपोर्टिंग सर्विसेज़ (Sentry, Firebase Crashlytics) को एरर भेजें
- कस्टम लॉग व्यूअर बनाएँ
- डीबगिंग के लिए लॉग्स स्टोर करें
- रियल-टाइम में ऐप व्यवहार मॉनिटर करें

``` dart
// Example: Send errors to Sentry
NyLogger.onLog = (entry) {
  if (entry.type == 'error') {
    Sentry.captureMessage(
      entry.message,
      level: SentryLevel.error,
    );
  }
};
```

<div id="helper-extensions"></div>

## हेल्पर एक्सटेंशन्स

{{ config('app.name') }} लॉगिंग के लिए सुविधाजनक एक्सटेंशन मेथड्स प्रदान करता है:

### dump()

कंसोल पर कोई भी वैल्यू प्रिंट करें:

``` dart
String project = 'Nylo';
project.dump(); // 'Nylo'

List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

int age = 25;
age.dump(); // 25

// Function syntax
dump("Hello World");
```

### dd() - डंप और डाई

वैल्यू प्रिंट करें और तुरंत बाहर निकलें (डीबगिंग के लिए उपयोगी):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## कॉन्फ़िगरेशन

### एनवायरनमेंट वेरिएबल्स

अपनी `.env` फ़ाइल में लॉगिंग व्यवहार नियंत्रित करें:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### लॉग्स में DateTime

{{ config('app.name') }} लॉग आउटपुट में टाइमस्टैम्प शामिल कर सकता है। इसे अपने Nylo सेटअप में कॉन्फ़िगर करें:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

टाइमस्टैम्प के साथ आउटपुट:
```
[2025-01-27 10:30:45] [info] User logged in
```

टाइमस्टैम्प के बिना आउटपुट:
```
[info] User logged in
```

### सर्वोत्तम प्रथाएँ

1. **उचित लॉग लेवल्स का उपयोग करें** - सब कुछ एरर के रूप में लॉग न करें
2. **प्रोडक्शन में वर्बोज़ लॉग्स हटाएँ** - प्रोडक्शन में `APP_DEBUG=false` रखें
3. **संदर्भ शामिल करें** - डीबगिंग के लिए प्रासंगिक डेटा लॉग करें
4. **स्ट्रक्चर्ड लॉगिंग का उपयोग करें** - कॉम्प्लेक्स डेटा के लिए `NyLogger.json()`
5. **एरर मॉनिटरिंग सेटअप करें** - एरर्स पकड़ने के लिए `NyLogger.onLog` का उपयोग करें

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

