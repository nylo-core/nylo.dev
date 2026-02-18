# Local Notifications

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Thông báo theo lịch](#scheduled-notifications "Thông báo theo lịch")
- [Mẫu Builder](#builder-pattern "Mẫu Builder")
- [Cấu hình nền tảng](#platform-configuration "Cấu hình nền tảng")
  - [Cấu hình Android](#android-configuration "Cấu hình Android")
  - [Cấu hình iOS](#ios-configuration "Cấu hình iOS")
- [Quản lý thông báo](#managing-notifications "Quản lý thông báo")
- [Quyền](#permissions "Quyền")
- [Thiết lập nền tảng](#platform-setup "Thiết lập nền tảng")
- [Tham chiếu API](#api-reference "Tham chiếu API")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} cung cấp hệ thống thông báo cục bộ thông qua lớp `LocalNotification`. Điều này cho phép bạn gửi thông báo tức thì hoặc theo lịch với nội dung phong phú trên iOS và Android.

> Thông báo cục bộ không được hỗ trợ trên web. Cố gắng sử dụng chúng trên web sẽ ném `NotificationException`.

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Gửi thông báo bằng mẫu builder hoặc phương thức tĩnh:

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

## Thông báo theo lịch

Lên lịch thông báo để gửi vào thời điểm cụ thể:

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

## Mẫu Builder

Lớp `LocalNotification` cung cấp API builder kiểu fluent. Tất cả phương thức có thể nối chuỗi đều trả về instance `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Phương thức có thể nối chuỗi

| Phương thức | Tham số | Mô tả |
|-------------|---------|-------|
| `addPayload` | `String payload` | Đặt chuỗi dữ liệu tùy chỉnh cho thông báo |
| `addId` | `int id` | Đặt định danh duy nhất cho thông báo |
| `addSubtitle` | `String subtitle` | Đặt văn bản phụ đề |
| `addBadgeNumber` | `int badgeNumber` | Đặt số badge biểu tượng ứng dụng |
| `addSound` | `String sound` | Đặt tên file âm thanh tùy chỉnh |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Thêm đính kèm (chỉ iOS, tải từ URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Đặt cấu hình riêng Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Đặt cấu hình riêng iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Nếu cung cấp `at`, lên lịch thông báo cho thời điểm đó. Nếu không, hiển thị ngay lập tức.

<div id="platform-configuration"></div>

## Cấu hình nền tảng

Trong v7, các tùy chọn riêng nền tảng được cấu hình thông qua đối tượng `AndroidNotificationConfig` và `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Cấu hình Android

Truyền `AndroidNotificationConfig` cho `setAndroidConfig()`:

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

#### Thuộc tính AndroidNotificationConfig

| Thuộc tính | Kiểu | Mặc định | Mô tả |
|------------|------|----------|-------|
| `channelId` | `String?` | `'default_channel'` | ID kênh thông báo |
| `channelName` | `String?` | `'Default Channel'` | Tên kênh thông báo |
| `channelDescription` | `String?` | `'Default Channel'` | Mô tả kênh |
| `importance` | `Importance?` | `Importance.max` | Mức độ quan trọng |
| `priority` | `Priority?` | `Priority.high` | Độ ưu tiên |
| `ticker` | `String?` | `'ticker'` | Văn bản ticker cho trợ năng |
| `icon` | `String?` | `'app_icon'` | Tên resource biểu tượng nhỏ |
| `playSound` | `bool?` | `true` | Có phát âm thanh không |
| `enableVibration` | `bool?` | `true` | Có bật rung không |
| `vibrationPattern` | `List<int>?` | - | Mẫu rung tùy chỉnh (ms) |
| `groupKey` | `String?` | - | Khóa nhóm cho nhóm thông báo |
| `setAsGroupSummary` | `bool?` | `false` | Có phải tóm tắt nhóm không |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Hành vi cảnh báo nhóm |
| `autoCancel` | `bool?` | `true` | Tự động ẩn khi nhấn |
| `ongoing` | `bool?` | `false` | Không thể bị người dùng loại bỏ |
| `silent` | `bool?` | `false` | Thông báo im lặng |
| `color` | `Color?` | - | Màu nhấn |
| `largeIcon` | `String?` | - | Đường dẫn resource biểu tượng lớn |
| `onlyAlertOnce` | `bool?` | `false` | Chỉ cảnh báo lần đầu hiển thị |
| `showWhen` | `bool?` | `true` | Hiển thị dấu thời gian |
| `when` | `int?` | - | Dấu thời gian tùy chỉnh (ms từ epoch) |
| `usesChronometer` | `bool?` | `false` | Sử dụng hiển thị đồng hồ |
| `chronometerCountDown` | `bool?` | `false` | Đồng hồ đếm ngược |
| `channelShowBadge` | `bool?` | `true` | Hiển thị badge trên kênh |
| `showProgress` | `bool?` | `false` | Hiển thị indicator tiến trình |
| `maxProgress` | `int?` | `0` | Giá trị tiến trình tối đa |
| `progress` | `int?` | `0` | Giá trị tiến trình hiện tại |
| `indeterminate` | `bool?` | `false` | Tiến trình không xác định |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Hành động kênh |
| `enableLights` | `bool?` | `false` | Bật LED thông báo |
| `ledColor` | `Color?` | - | Màu LED |
| `ledOnMs` | `int?` | - | Thời gian bật LED (ms) |
| `ledOffMs` | `int?` | - | Thời gian tắt LED (ms) |
| `visibility` | `NotificationVisibility?` | - | Hiển thị trên màn hình khóa |
| `timeoutAfter` | `int?` | - | Thời gian tự động ẩn (ms) |
| `fullScreenIntent` | `bool?` | `false` | Khởi chạy dạng toàn màn hình |
| `shortcutId` | `String?` | - | ID phím tắt |
| `additionalFlags` | `List<int>?` | - | Cờ bổ sung |
| `tag` | `String?` | - | Tag thông báo |
| `actions` | `List<AndroidNotificationAction>?` | - | Nút hành động |
| `colorized` | `bool?` | `false` | Bật tô màu |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Sử dụng thuộc tính âm thanh |

<div id="ios-configuration"></div>

### Cấu hình iOS

Truyền `IOSNotificationConfig` cho `setIOSConfig()`:

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

#### Thuộc tính IOSNotificationConfig

| Thuộc tính | Kiểu | Mặc định | Mô tả |
|------------|------|----------|-------|
| `presentList` | `bool?` | `true` | Hiển thị trong danh sách thông báo |
| `presentAlert` | `bool?` | `true` | Hiển thị cảnh báo |
| `presentBadge` | `bool?` | `true` | Cập nhật badge ứng dụng |
| `presentSound` | `bool?` | `true` | Phát âm thanh |
| `presentBanner` | `bool?` | `true` | Hiển thị banner |
| `sound` | `String?` | - | Tên file âm thanh |
| `badgeNumber` | `int?` | - | Số badge |
| `threadIdentifier` | `String?` | - | Định danh luồng cho nhóm |
| `categoryIdentifier` | `String?` | - | Định danh danh mục cho hành động |
| `interruptionLevel` | `InterruptionLevel?` | - | Mức độ gián đoạn |

### Đính kèm (chỉ iOS)

Thêm đính kèm hình ảnh, âm thanh, hoặc video cho thông báo iOS. Đính kèm được tải từ URL:

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

## Quản lý thông báo

### Hủy thông báo cụ thể

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Hủy tất cả thông báo

``` dart
await LocalNotification.cancelAllNotifications();
```

### Xóa số badge

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Quyền

Yêu cầu quyền thông báo từ người dùng:

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

Quyền được tự động yêu cầu trong lần gửi thông báo đầu tiên qua `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Thiết lập nền tảng

### Thiết lập iOS

Thêm vào `Info.plist`:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Thiết lập Android

Thêm vào `AndroidManifest.xml`:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Tham chiếu API

### Phương thức tĩnh

| Phương thức | Chữ ký | Mô tả |
|-------------|--------|-------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Gửi thông báo với tất cả tùy chọn |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Hủy thông báo cụ thể |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Hủy tất cả thông báo |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Yêu cầu quyền thông báo |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Xóa số badge iOS |

### Phương thức Instance (có thể nối chuỗi)

| Phương thức | Tham số | Trả về | Mô tả |
|-------------|---------|--------|-------|
| `addPayload` | `String payload` | `LocalNotification` | Đặt dữ liệu payload |
| `addId` | `int id` | `LocalNotification` | Đặt ID thông báo |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Đặt phụ đề |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Đặt số badge |
| `addSound` | `String sound` | `LocalNotification` | Đặt âm thanh tùy chỉnh |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Thêm đính kèm (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Đặt cấu hình Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Đặt cấu hình iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Gửi thông báo |
