# Local Notifications

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานพื้นฐาน](#basic-usage "การใช้งานพื้นฐาน")
- [การแจ้งเตือนตามกำหนดเวลา](#scheduled-notifications "การแจ้งเตือนตามกำหนดเวลา")
- [รูปแบบ Builder](#builder-pattern "รูปแบบ Builder")
- [การตั้งค่าเฉพาะแพลตฟอร์ม](#platform-configuration "การตั้งค่าเฉพาะแพลตฟอร์ม")
  - [การตั้งค่า Android](#android-configuration "การตั้งค่า Android")
  - [การตั้งค่า iOS](#ios-configuration "การตั้งค่า iOS")
- [การจัดการการแจ้งเตือน](#managing-notifications "การจัดการการแจ้งเตือน")
- [สิทธิ์การเข้าถึง](#permissions "สิทธิ์การเข้าถึง")
- [การตั้งค่าแพลตฟอร์ม](#platform-setup "การตั้งค่าแพลตฟอร์ม")
- [เอกสารอ้างอิง API](#api-reference "เอกสารอ้างอิง API")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} มีระบบการแจ้งเตือนภายในเครื่องผ่านคลาส `LocalNotification` ซึ่งช่วยให้คุณส่งการแจ้งเตือนทันทีหรือตามกำหนดเวลาพร้อมเนื้อหาที่หลากหลายบน iOS และ Android

> การแจ้งเตือนภายในเครื่องไม่รองรับบนเว็บ การพยายามใช้งานบนเว็บจะเกิดข้อผิดพลาด `NotificationException`

<div id="basic-usage"></div>

## การใช้งานพื้นฐาน

ส่งการแจ้งเตือนโดยใช้รูปแบบ builder หรือเมธอดแบบ static:

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

## การแจ้งเตือนตามกำหนดเวลา

กำหนดเวลาการแจ้งเตือนให้ส่งในเวลาที่กำหนด:

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

## รูปแบบ Builder

คลาส `LocalNotification` มี API แบบ fluent builder เมธอดที่เชื่อมต่อได้ทั้งหมดจะส่งคืนอินสแตนซ์ `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### เมธอดที่เชื่อมต่อได้

| เมธอด | พารามิเตอร์ | คำอธิบาย |
|--------|------------|-------------|
| `addPayload` | `String payload` | ตั้งค่าสตริงข้อมูลที่กำหนดเองสำหรับการแจ้งเตือน |
| `addId` | `int id` | ตั้งค่าตัวระบุเฉพาะสำหรับการแจ้งเตือน |
| `addSubtitle` | `String subtitle` | ตั้งค่าข้อความหัวข้อรอง |
| `addBadgeNumber` | `int badgeNumber` | ตั้งค่าหมายเลขป้ายไอคอนแอป |
| `addSound` | `String sound` | ตั้งค่าชื่อไฟล์เสียงที่กำหนดเอง |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | เพิ่มไฟล์แนบ (เฉพาะ iOS ดาวน์โหลดจาก URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | ตั้งค่าการกำหนดค่าเฉพาะ Android |
| `setIOSConfig` | `IOSNotificationConfig config` | ตั้งค่าการกำหนดค่าเฉพาะ iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

หาก `at` ถูกระบุ จะกำหนดเวลาการแจ้งเตือนตามเวลานั้น มิฉะนั้นจะแสดงทันที

<div id="platform-configuration"></div>

## การตั้งค่าเฉพาะแพลตฟอร์ม

ใน v7 ตัวเลือกเฉพาะแพลตฟอร์มจะถูกกำหนดค่าผ่านอ็อบเจกต์ `AndroidNotificationConfig` และ `IOSNotificationConfig`

<div id="android-configuration"></div>

### การตั้งค่า Android

ส่ง `AndroidNotificationConfig` ไปยัง `setAndroidConfig()`:

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

#### คุณสมบัติ AndroidNotificationConfig

| คุณสมบัติ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | ID ช่องทางการแจ้งเตือน |
| `channelName` | `String?` | `'Default Channel'` | ชื่อช่องทางการแจ้งเตือน |
| `channelDescription` | `String?` | `'Default Channel'` | คำอธิบายช่องทาง |
| `importance` | `Importance?` | `Importance.max` | ระดับความสำคัญของการแจ้งเตือน |
| `priority` | `Priority?` | `Priority.high` | ลำดับความสำคัญของการแจ้งเตือน |
| `ticker` | `String?` | `'ticker'` | ข้อความ ticker สำหรับการเข้าถึง |
| `icon` | `String?` | `'app_icon'` | ชื่อทรัพยากรไอคอนขนาดเล็ก |
| `playSound` | `bool?` | `true` | เล่นเสียงหรือไม่ |
| `enableVibration` | `bool?` | `true` | เปิดใช้การสั่นหรือไม่ |
| `vibrationPattern` | `List<int>?` | - | รูปแบบการสั่นที่กำหนดเอง (มิลลิวินาที) |
| `groupKey` | `String?` | - | คีย์กลุ่มสำหรับการจัดกลุ่มการแจ้งเตือน |
| `setAsGroupSummary` | `bool?` | `false` | เป็นสรุปกลุ่มหรือไม่ |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | พฤติกรรมการแจ้งเตือนสำหรับกลุ่ม |
| `autoCancel` | `bool?` | `true` | ปิดอัตโนมัติเมื่อแตะ |
| `ongoing` | `bool?` | `false` | ผู้ใช้ไม่สามารถปิดได้ |
| `silent` | `bool?` | `false` | การแจ้งเตือนแบบเงียบ |
| `color` | `Color?` | - | สีเน้น |
| `largeIcon` | `String?` | - | เส้นทางทรัพยากรไอคอนขนาดใหญ่ |
| `onlyAlertOnce` | `bool?` | `false` | แจ้งเตือนเฉพาะครั้งแรก |
| `showWhen` | `bool?` | `true` | แสดง timestamp |
| `when` | `int?` | - | timestamp ที่กำหนดเอง (มิลลิวินาทีตั้งแต่ epoch) |
| `usesChronometer` | `bool?` | `false` | ใช้การแสดงผลนาฬิกาจับเวลา |
| `chronometerCountDown` | `bool?` | `false` | นาฬิกาจับเวลานับถอยหลัง |
| `channelShowBadge` | `bool?` | `true` | แสดงป้ายบนช่องทาง |
| `showProgress` | `bool?` | `false` | แสดงตัวบ่งชี้ความคืบหน้า |
| `maxProgress` | `int?` | `0` | ค่าความคืบหน้าสูงสุด |
| `progress` | `int?` | `0` | ค่าความคืบหน้าปัจจุบัน |
| `indeterminate` | `bool?` | `false` | ความคืบหน้าไม่แน่นอน |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | การดำเนินการช่องทาง |
| `enableLights` | `bool?` | `false` | เปิดใช้ LED การแจ้งเตือน |
| `ledColor` | `Color?` | - | สี LED |
| `ledOnMs` | `int?` | - | ระยะเวลา LED เปิด (มิลลิวินาที) |
| `ledOffMs` | `int?` | - | ระยะเวลา LED ปิด (มิลลิวินาที) |
| `visibility` | `NotificationVisibility?` | - | การมองเห็นบนหน้าจอล็อก |
| `timeoutAfter` | `int?` | - | ปิดอัตโนมัติหลังเวลาที่กำหนด (มิลลิวินาที) |
| `fullScreenIntent` | `bool?` | `false` | เปิดเป็น full screen intent |
| `shortcutId` | `String?` | - | ID ทางลัด |
| `additionalFlags` | `List<int>?` | - | แฟล็กเพิ่มเติม |
| `tag` | `String?` | - | แท็กการแจ้งเตือน |
| `actions` | `List<AndroidNotificationAction>?` | - | ปุ่มการดำเนินการ |
| `colorized` | `bool?` | `false` | เปิดใช้การเติมสี |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | การใช้งานคุณลักษณะเสียง |

<div id="ios-configuration"></div>

### การตั้งค่า iOS

ส่ง `IOSNotificationConfig` ไปยัง `setIOSConfig()`:

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

#### คุณสมบัติ IOSNotificationConfig

| คุณสมบัติ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | แสดงในรายการการแจ้งเตือน |
| `presentAlert` | `bool?` | `true` | แสดงการแจ้งเตือน |
| `presentBadge` | `bool?` | `true` | อัปเดตป้ายแอป |
| `presentSound` | `bool?` | `true` | เล่นเสียง |
| `presentBanner` | `bool?` | `true` | แสดงแบนเนอร์ |
| `sound` | `String?` | - | ชื่อไฟล์เสียง |
| `badgeNumber` | `int?` | - | หมายเลขป้าย |
| `threadIdentifier` | `String?` | - | ตัวระบุเธรดสำหรับการจัดกลุ่ม |
| `categoryIdentifier` | `String?` | - | ตัวระบุหมวดหมู่สำหรับการดำเนินการ |
| `interruptionLevel` | `InterruptionLevel?` | - | ระดับการขัดจังหวะ |

### ไฟล์แนบ (เฉพาะ iOS)

เพิ่มไฟล์แนบรูปภาพ เสียง หรือวิดีโอให้กับการแจ้งเตือน iOS ไฟล์แนบจะถูกดาวน์โหลดจาก URL:

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

## การจัดการการแจ้งเตือน

### ยกเลิกการแจ้งเตือนที่ระบุ

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### ยกเลิกการแจ้งเตือนทั้งหมด

``` dart
await LocalNotification.cancelAllNotifications();
```

### ล้างจำนวนป้าย

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## สิทธิ์การเข้าถึง

ขอสิทธิ์การแจ้งเตือนจากผู้ใช้:

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

สิทธิ์จะถูกร้องขออัตโนมัติเมื่อส่งการแจ้งเตือนครั้งแรกผ่าน `NyScheduler.taskOnce`

<div id="platform-setup"></div>

## การตั้งค่าแพลตฟอร์ม

### การตั้งค่า iOS

เพิ่มลงใน `Info.plist` ของคุณ:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### การตั้งค่า Android

เพิ่มลงใน `AndroidManifest.xml` ของคุณ:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## เอกสารอ้างอิง API

### เมธอดแบบ Static

| เมธอด | ลายเซ็น | คำอธิบาย |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | ส่งการแจ้งเตือนพร้อมตัวเลือกทั้งหมด |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | ยกเลิกการแจ้งเตือนที่ระบุ |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | ยกเลิกการแจ้งเตือนทั้งหมด |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | ขอสิทธิ์การแจ้งเตือน |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | ล้างจำนวนป้ายบน iOS |

### เมธอดอินสแตนซ์ (เชื่อมต่อได้)

| เมธอด | พารามิเตอร์ | ส่งคืน | คำอธิบาย |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | ตั้งค่าข้อมูล payload |
| `addId` | `int id` | `LocalNotification` | ตั้งค่า ID การแจ้งเตือน |
| `addSubtitle` | `String subtitle` | `LocalNotification` | ตั้งค่าหัวข้อรอง |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | ตั้งค่าหมายเลขป้าย |
| `addSound` | `String sound` | `LocalNotification` | ตั้งค่าเสียงที่กำหนดเอง |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | เพิ่มไฟล์แนบ (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | ตั้งค่า config Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | ตั้งค่า config iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | ส่งการแจ้งเตือน |
