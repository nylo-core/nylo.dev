# ऐप उपयोग

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [सेटअप](#setup "सेटअप")
- मॉनिटरिंग
    - [ऐप लॉन्च](#monitoring-app-launches "ऐप लॉन्च")
    - [पहली लॉन्च तिथि](#monitoring-app-first-launch-date "पहली लॉन्च तिथि")
    - [पहले लॉन्च से कुल दिन](#monitoring-app-total-days-since-first-launch "पहले लॉन्च से कुल दिन")

<div id="introduction"></div>

## परिचय

Nylo आपको अपने ऐप उपयोग को बॉक्स से बाहर मॉनिटर करने की अनुमति देता है लेकिन पहले आपको अपने किसी ऐप प्रोवाइडर में इस फ़ीचर को सक्षम करना होगा।

वर्तमान में, Nylo निम्नलिखित मॉनिटर कर सकता है:

- ऐप लॉन्च
- पहली लॉन्च तिथि

यह डॉक्यूमेंटेशन पढ़ने के बाद, आप सीखेंगे कि अपने ऐप उपयोग को कैसे मॉनिटर करें।

<div id="setup"></div>

## सेटअप

अपनी `app/providers/app_provider.dart` फ़ाइल खोलें।

फिर, अपने `boot` मेथड में निम्नलिखित कोड जोड़ें।

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

यह आपके ऐप में ऐप उपयोग मॉनिटरिंग सक्षम करेगा। यदि आपको कभी जाँचना हो कि ऐप उपयोग मॉनिटरिंग सक्षम है या नहीं, तो आप `Nylo.instance.shouldMonitorAppUsage()` मेथड का उपयोग कर सकते हैं।

<div id="monitoring-app-launches"></div>

## ऐप लॉन्च मॉनिटर करना

आप `Nylo.appLaunchCount` मेथड का उपयोग करके अपने ऐप के लॉन्च होने की संख्या मॉनिटर कर सकते हैं।

> ऐप लॉन्च हर बार गिने जाते हैं जब ऐप बंद स्थिति से खोला जाता है।

इस मेथड का उपयोग करने का एक सरल उदाहरण:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## ऐप पहली लॉन्च तिथि मॉनिटर करना

आप `Nylo.appFirstLaunchDate` मेथड का उपयोग करके अपने ऐप के पहली बार लॉन्च होने की तिथि मॉनिटर कर सकते हैं।

इस मेथड का उपयोग करने का एक उदाहरण:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## पहले लॉन्च से कुल दिन मॉनिटर करना

आप `Nylo.appTotalDaysSinceFirstLaunch` मेथड का उपयोग करके अपने ऐप के पहली बार लॉन्च होने के बाद से कुल दिनों को मॉनिटर कर सकते हैं।

इस मेथड का उपयोग करने का एक उदाहरण:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
