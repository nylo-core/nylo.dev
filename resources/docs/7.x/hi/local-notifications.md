# Local Notifications

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
- [शेड्यूल्ड नोटिफ़िकेशन्स](#scheduled-notifications "शेड्यूल्ड नोटिफ़िकेशन्स")
- [बिल्डर पैटर्न](#builder-pattern "बिल्डर पैटर्न")
- [प्लेटफ़ॉर्म कॉन्फ़िगरेशन](#platform-configuration "प्लेटफ़ॉर्म कॉन्फ़िगरेशन")
  - [Android कॉन्फ़िगरेशन](#android-configuration "Android कॉन्फ़िगरेशन")
  - [iOS कॉन्फ़िगरेशन](#ios-configuration "iOS कॉन्फ़िगरेशन")
- [नोटिफ़िकेशन्स प्रबंधित करना](#managing-notifications "नोटिफ़िकेशन्स प्रबंधित करना")
- [अनुमतियाँ](#permissions "अनुमतियाँ")
- [प्लेटफ़ॉर्म सेटअप](#platform-setup "प्लेटफ़ॉर्म सेटअप")
- [API रेफरेंस](#api-reference "API रेफरेंस")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} `LocalNotification` क्लास के माध्यम से एक लोकल नोटिफ़िकेशन सिस्टम प्रदान करता है। यह आपको iOS और Android पर रिच कंटेंट के साथ तत्काल या शेड्यूल्ड नोटिफ़िकेशन भेजने की अनुमति देता है।

> लोकल नोटिफ़िकेशन्स वेब पर सपोर्टेड नहीं हैं। वेब पर इन्हें उपयोग करने का प्रयास करने पर `NotificationException` थ्रो होगा।

<div id="basic-usage"></div>

## बेसिक उपयोग

बिल्डर पैटर्न या स्टैटिक मेथड का उपयोग करके नोटिफ़िकेशन भेजें:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Builder pattern
await LocalNotification(title: "Hello", body: "World").send();

// Using the helper function
await localNotification("Hello", "World").send();

// Using the static method
await LocalNotification.sendNotification(
  title: "Hello",
  body: "World",
);
```

<div id="scheduled-notifications"></div>

## शेड्यूल्ड नोटिफ़िकेशन्स

किसी विशिष्ट समय पर डिलीवर होने के लिए नोटिफ़िकेशन शेड्यूल करें:

``` dart
// Schedule for tomorrow
final tomorrow = DateTime.now().add(Duration(days: 1));

await LocalNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
).send(at: tomorrow);

// Using the static method
await LocalNotification.sendNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
  at: tomorrow,
);
```

<div id="builder-pattern"></div>

## बिल्डर पैटर्न

`LocalNotification` क्लास एक फ्लुएंट बिल्डर API प्रदान करता है। सभी चेनेबल मेथड्स `LocalNotification` इंस्टेंस लौटाते हैं:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### चेनेबल मेथड्स

| मेथड | पैरामीटर्स | विवरण |
|--------|------------|-------------|
| `addPayload` | `String payload` | नोटिफ़िकेशन के लिए कस्टम डेटा स्ट्रिंग सेट करता है |
| `addId` | `int id` | नोटिफ़िकेशन के लिए एक यूनिक आइडेंटिफ़ायर सेट करता है |
| `addSubtitle` | `String subtitle` | सबटाइटल टेक्स्ट सेट करता है |
| `addBadgeNumber` | `int badgeNumber` | ऐप आइकन बैज नंबर सेट करता है |
| `addSound` | `String sound` | कस्टम साउंड फ़ाइल नाम सेट करता है |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | अटैचमेंट जोड़ता है (केवल iOS, URL से डाउनलोड करता है) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Android-विशिष्ट कॉन्फ़िगरेशन सेट करता है |
| `setIOSConfig` | `IOSNotificationConfig config` | iOS-विशिष्ट कॉन्फ़िगरेशन सेट करता है |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

यदि `at` प्रदान किया गया है, तो उस समय के लिए नोटिफ़िकेशन शेड्यूल करता है। अन्यथा तुरंत दिखाता है।

<div id="platform-configuration"></div>

## प्लेटफ़ॉर्म कॉन्फ़िगरेशन

प्लेटफ़ॉर्म-विशिष्ट विकल्प `AndroidNotificationConfig` और `IOSNotificationConfig` ऑब्जेक्ट्स के माध्यम से कॉन्फ़िगर किए जाते हैं।

<div id="android-configuration"></div>

### Android कॉन्फ़िगरेशन

`setAndroidConfig()` में `AndroidNotificationConfig` पास करें:

``` dart
await LocalNotification(
  title: "Android Notification",
  body: "With custom configuration",
)
.setAndroidConfig(AndroidNotificationConfig(
  channelId: "custom_channel",
  channelName: "Custom Channel",
  channelDescription: "Notifications from custom channel",
  importance: Importance.max,
  priority: Priority.high,
  enableVibration: true,
  vibrationPattern: [0, 1000, 500, 1000],
  enableLights: true,
  ledColor: Color(0xFF00FF00),
))
.send();
```

#### AndroidNotificationConfig प्रॉपर्टीज़

| प्रॉपर्टी | टाइप | डिफ़ॉल्ट | विवरण |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | नोटिफ़िकेशन चैनल ID |
| `channelName` | `String?` | `'Default Channel'` | नोटिफ़िकेशन चैनल नाम |
| `channelDescription` | `String?` | `'Default Channel'` | चैनल विवरण |
| `importance` | `Importance?` | `Importance.max` | नोटिफ़िकेशन महत्व स्तर |
| `priority` | `Priority?` | `Priority.high` | नोटिफ़िकेशन प्राथमिकता |
| `ticker` | `String?` | `'ticker'` | एक्सेसिबिलिटी के लिए टिकर टेक्स्ट |
| `icon` | `String?` | `'app_icon'` | स्मॉल आइकन रिसोर्स नाम |
| `playSound` | `bool?` | `true` | ध्वनि बजानी है या नहीं |
| `enableVibration` | `bool?` | `true` | वाइब्रेशन सक्षम करना है या नहीं |
| `vibrationPattern` | `List<int>?` | - | कस्टम वाइब्रेशन पैटर्न (ms) |
| `groupKey` | `String?` | - | नोटिफ़िकेशन ग्रुपिंग के लिए ग्रुप की |
| `setAsGroupSummary` | `bool?` | `false` | यह ग्रुप सारांश है या नहीं |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | ग्रुप के लिए अलर्ट व्यवहार |
| `autoCancel` | `bool?` | `true` | टैप पर ऑटो-डिसमिस |
| `ongoing` | `bool?` | `false` | यूज़र द्वारा डिसमिस नहीं किया जा सकता |
| `silent` | `bool?` | `false` | साइलेंट नोटिफ़िकेशन |
| `color` | `Color?` | - | एक्सेंट रंग |
| `largeIcon` | `String?` | - | लार्ज आइकन रिसोर्स पथ |
| `onlyAlertOnce` | `bool?` | `false` | केवल पहली बार दिखाने पर अलर्ट |
| `showWhen` | `bool?` | `true` | टाइमस्टैम्प दिखाएँ |
| `when` | `int?` | - | कस्टम टाइमस्टैम्प (epoch से ms) |
| `usesChronometer` | `bool?` | `false` | क्रोनोमीटर डिस्प्ले का उपयोग करें |
| `chronometerCountDown` | `bool?` | `false` | क्रोनोमीटर उल्टी गिनती करता है |
| `channelShowBadge` | `bool?` | `true` | चैनल पर बैज दिखाएँ |
| `showProgress` | `bool?` | `false` | प्रोग्रेस इंडिकेटर दिखाएँ |
| `maxProgress` | `int?` | `0` | अधिकतम प्रोग्रेस वैल्यू |
| `progress` | `int?` | `0` | वर्तमान प्रोग्रेस वैल्यू |
| `indeterminate` | `bool?` | `false` | अनिश्चित प्रोग्रेस |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | चैनल एक्शन |
| `enableLights` | `bool?` | `false` | नोटिफ़िकेशन LED सक्षम करें |
| `ledColor` | `Color?` | - | LED रंग |
| `ledOnMs` | `int?` | - | LED ऑन अवधि (ms) |
| `ledOffMs` | `int?` | - | LED ऑफ़ अवधि (ms) |
| `visibility` | `NotificationVisibility?` | - | लॉक स्क्रीन विज़िबिलिटी |
| `timeoutAfter` | `int?` | - | ऑटो-डिसमिस टाइमआउट (ms) |
| `fullScreenIntent` | `bool?` | `false` | फ़ुल स्क्रीन इंटेंट के रूप में लॉन्च करें |
| `shortcutId` | `String?` | - | शॉर्टकट ID |
| `additionalFlags` | `List<int>?` | - | अतिरिक्त फ्लैग्स |
| `tag` | `String?` | - | नोटिफ़िकेशन टैग |
| `actions` | `List<AndroidNotificationAction>?` | - | एक्शन बटन |
| `colorized` | `bool?` | `false` | कलराइज़ेशन सक्षम करें |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | ऑडियो एट्रिब्यूट्स उपयोग |

<div id="ios-configuration"></div>

### iOS कॉन्फ़िगरेशन

`setIOSConfig()` में `IOSNotificationConfig` पास करें:

``` dart
await LocalNotification(
  title: "iOS Notification",
  body: "With custom configuration",
)
.setIOSConfig(IOSNotificationConfig(
  presentAlert: true,
  presentBanner: true,
  presentSound: true,
  threadIdentifier: "thread_1",
  interruptionLevel: InterruptionLevel.active,
))
.addBadgeNumber(1)
.addSound("custom_sound.wav")
.send();
```

#### IOSNotificationConfig प्रॉपर्टीज़

| प्रॉपर्टी | टाइप | डिफ़ॉल्ट | विवरण |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | नोटिफ़िकेशन लिस्ट में प्रस्तुत करें |
| `presentAlert` | `bool?` | `true` | अलर्ट प्रस्तुत करें |
| `presentBadge` | `bool?` | `true` | ऐप बैज अपडेट करें |
| `presentSound` | `bool?` | `true` | ध्वनि बजाएँ |
| `presentBanner` | `bool?` | `true` | बैनर प्रस्तुत करें |
| `sound` | `String?` | - | साउंड फ़ाइल नाम |
| `badgeNumber` | `int?` | - | बैज नंबर |
| `threadIdentifier` | `String?` | - | ग्रुपिंग के लिए थ्रेड आइडेंटिफ़ायर |
| `categoryIdentifier` | `String?` | - | एक्शन के लिए कैटेगरी आइडेंटिफ़ायर |
| `interruptionLevel` | `InterruptionLevel?` | - | इंटरप्शन लेवल |

### अटैचमेंट्स (केवल iOS)

iOS नोटिफ़िकेशन में इमेज, ऑडियो, या वीडियो अटैचमेंट जोड़ें। अटैचमेंट URL से डाउनलोड किए जाते हैं:

``` dart
await LocalNotification(
  title: "New Photo",
  body: "Check out this image!",
)
.addAttachment(
  "https://example.com/image.jpg",
  "photo.jpg",
  showThumbnail: true,
)
.send();
```

<div id="managing-notifications"></div>

## नोटिफ़िकेशन्स प्रबंधित करना

### किसी विशिष्ट नोटिफ़िकेशन को रद्द करें

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### सभी नोटिफ़िकेशन्स रद्द करें

``` dart
await LocalNotification.cancelAllNotifications();
```

### बैज काउंट क्लियर करें

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## अनुमतियाँ

यूज़र से नोटिफ़िकेशन अनुमतियाँ अनुरोध करें:

``` dart
// Request with defaults
await LocalNotification.requestPermissions();

// Request with specific options
await LocalNotification.requestPermissions(
  alert: true,
  badge: true,
  sound: true,
  provisional: false,
  critical: false,
  vibrate: true,
  enableLights: true,
  channelId: 'default_notification_channel_id',
  channelName: 'Default Notification Channel',
);
```

अनुमतियाँ `NyScheduler.taskOnce` के माध्यम से पहले नोटिफ़िकेशन भेजने पर स्वचालित रूप से अनुरोध की जाती हैं।

<div id="platform-setup"></div>

## प्लेटफ़ॉर्म सेटअप

### iOS सेटअप

अपनी `Info.plist` में जोड़ें:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android सेटअप

अपनी `AndroidManifest.xml` में जोड़ें:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## API रेफरेंस

### स्टैटिक मेथड्स

| मेथड | सिग्नेचर | विवरण |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | सभी विकल्पों के साथ नोटिफ़िकेशन भेजें |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | किसी विशिष्ट नोटिफ़िकेशन को रद्द करें |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | सभी नोटिफ़िकेशन्स रद्द करें |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | नोटिफ़िकेशन अनुमतियाँ अनुरोध करें |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | iOS बैज काउंट क्लियर करें |

### इंस्टेंस मेथड्स (चेनेबल)

| मेथड | पैरामीटर्स | लौटाता है | विवरण |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | पेलोड डेटा सेट करें |
| `addId` | `int id` | `LocalNotification` | नोटिफ़िकेशन ID सेट करें |
| `addSubtitle` | `String subtitle` | `LocalNotification` | सबटाइटल सेट करें |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | बैज नंबर सेट करें |
| `addSound` | `String sound` | `LocalNotification` | कस्टम साउंड सेट करें |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | अटैचमेंट जोड़ें (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Android कॉन्फ़िग सेट करें |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | iOS कॉन्फ़िग सेट करें |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | नोटिफ़िकेशन भेजें |

