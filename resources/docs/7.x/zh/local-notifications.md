# 本地通知

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [定时通知](#scheduled-notifications "定时通知")
- [构建器模式](#builder-pattern "构建器模式")
- [平台配置](#platform-configuration "平台配置")
  - [Android 配置](#android-configuration "Android 配置")
  - [iOS 配置](#ios-configuration "iOS 配置")
- [管理通知](#managing-notifications "管理通知")
- [权限](#permissions "权限")
- [平台设置](#platform-setup "平台设置")
- [API 参考](#api-reference "API 参考")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} 通过 `LocalNotification` 类提供本地通知系统。这允许您在 iOS 和 Android 上发送即时或定时的富内容通知。

> 本地通知不支持 Web 平台。在 Web 上尝试使用它们将抛出 `NotificationException`。

<div id="basic-usage"></div>

## 基本用法

使用构建器模式或静态方法发送通知：

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

## 定时通知

安排通知在特定时间发送：

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

## 构建器模式

`LocalNotification` 类提供流畅的构建器 API。所有链式方法都返回 `LocalNotification` 实例：

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### 链式方法

| 方法 | 参数 | 描述 |
|--------|------------|-------------|
| `addPayload` | `String payload` | 设置通知的自定义数据字符串 |
| `addId` | `int id` | 设置通知的唯一标识符 |
| `addSubtitle` | `String subtitle` | 设置副标题文本 |
| `addBadgeNumber` | `int badgeNumber` | 设置应用图标角标数字 |
| `addSound` | `String sound` | 设置自定义声音文件名 |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | 添加附件（仅 iOS，从 URL 下载） |
| `setAndroidConfig` | `AndroidNotificationConfig config` | 设置 Android 特定配置 |
| `setIOSConfig` | `IOSNotificationConfig config` | 设置 iOS 特定配置 |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

如果提供了 `at` 参数，则在该时间安排通知。否则立即显示。

<div id="platform-configuration"></div>

## 平台配置

平台特定选项通过 `AndroidNotificationConfig` 和 `IOSNotificationConfig` 对象进行配置。

<div id="android-configuration"></div>

### Android 配置

将 `AndroidNotificationConfig` 传递给 `setAndroidConfig()`：

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

#### AndroidNotificationConfig 属性

| 属性 | 类型 | 默认值 | 描述 |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | 通知渠道 ID |
| `channelName` | `String?` | `'Default Channel'` | 通知渠道名称 |
| `channelDescription` | `String?` | `'Default Channel'` | 渠道描述 |
| `importance` | `Importance?` | `Importance.max` | 通知重要性级别 |
| `priority` | `Priority?` | `Priority.high` | 通知优先级 |
| `ticker` | `String?` | `'ticker'` | 无障碍功能的 Ticker 文本 |
| `icon` | `String?` | `'app_icon'` | 小图标资源名称 |
| `playSound` | `bool?` | `true` | 是否播放声音 |
| `enableVibration` | `bool?` | `true` | 是否启用振动 |
| `vibrationPattern` | `List<int>?` | - | 自定义振动模式（毫秒） |
| `groupKey` | `String?` | - | 通知分组的组键 |
| `setAsGroupSummary` | `bool?` | `false` | 是否为组摘要 |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | 组的提醒行为 |
| `autoCancel` | `bool?` | `true` | 点击后自动消除 |
| `ongoing` | `bool?` | `false` | 用户无法手动消除 |
| `silent` | `bool?` | `false` | 静默通知 |
| `color` | `Color?` | - | 强调色 |
| `largeIcon` | `String?` | - | 大图标资源路径 |
| `onlyAlertOnce` | `bool?` | `false` | 仅在首次显示时提醒 |
| `showWhen` | `bool?` | `true` | 显示时间戳 |
| `when` | `int?` | - | 自定义时间戳（自纪元起的毫秒数） |
| `usesChronometer` | `bool?` | `false` | 使用计时器显示 |
| `chronometerCountDown` | `bool?` | `false` | 计时器倒计时 |
| `channelShowBadge` | `bool?` | `true` | 在渠道上显示角标 |
| `showProgress` | `bool?` | `false` | 显示进度指示器 |
| `maxProgress` | `int?` | `0` | 最大进度值 |
| `progress` | `int?` | `0` | 当前进度值 |
| `indeterminate` | `bool?` | `false` | 不确定进度 |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | 渠道操作 |
| `enableLights` | `bool?` | `false` | 启用通知 LED |
| `ledColor` | `Color?` | - | LED 颜色 |
| `ledOnMs` | `int?` | - | LED 亮起持续时间（毫秒） |
| `ledOffMs` | `int?` | - | LED 熄灭持续时间（毫秒） |
| `visibility` | `NotificationVisibility?` | - | 锁屏可见性 |
| `timeoutAfter` | `int?` | - | 自动消除超时（毫秒） |
| `fullScreenIntent` | `bool?` | `false` | 作为全屏意图启动 |
| `shortcutId` | `String?` | - | 快捷方式 ID |
| `additionalFlags` | `List<int>?` | - | 附加标志 |
| `tag` | `String?` | - | 通知标签 |
| `actions` | `List<AndroidNotificationAction>?` | - | 操作按钮 |
| `colorized` | `bool?` | `false` | 启用着色 |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | 音频属性用途 |

<div id="ios-configuration"></div>

### iOS 配置

将 `IOSNotificationConfig` 传递给 `setIOSConfig()`：

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

#### IOSNotificationConfig 属性

| 属性 | 类型 | 默认值 | 描述 |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | 在通知列表中显示 |
| `presentAlert` | `bool?` | `true` | 显示弹窗提醒 |
| `presentBadge` | `bool?` | `true` | 更新应用角标 |
| `presentSound` | `bool?` | `true` | 播放声音 |
| `presentBanner` | `bool?` | `true` | 显示横幅 |
| `sound` | `String?` | - | 声音文件名 |
| `badgeNumber` | `int?` | - | 角标数字 |
| `threadIdentifier` | `String?` | - | 用于分组的线程标识符 |
| `categoryIdentifier` | `String?` | - | 用于操作的类别标识符 |
| `interruptionLevel` | `InterruptionLevel?` | - | 中断级别 |

### 附件（仅 iOS）

为 iOS 通知添加图片、音频或视频附件。附件从 URL 下载：

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

## 管理通知

### 取消特定通知

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### 取消所有通知

``` dart
await LocalNotification.cancelAllNotifications();
```

### 清除角标计数

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## 权限

向用户请求通知权限：

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

权限会在首次通过 `NyScheduler.taskOnce` 发送通知时自动请求。

<div id="platform-setup"></div>

## 平台设置

### iOS 设置

在您的 `Info.plist` 中添加：

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android 设置

在您的 `AndroidManifest.xml` 中添加：

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## API 参考

### 静态方法

| 方法 | 签名 | 描述 |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | 使用所有选项发送通知 |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | 取消特定通知 |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | 取消所有通知 |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | 请求通知权限 |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | 清除 iOS 角标计数 |

### 实例方法（链式调用）

| 方法 | 参数 | 返回值 | 描述 |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | 设置负载数据 |
| `addId` | `int id` | `LocalNotification` | 设置通知 ID |
| `addSubtitle` | `String subtitle` | `LocalNotification` | 设置副标题 |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | 设置角标数字 |
| `addSound` | `String sound` | `LocalNotification` | 设置自定义声音 |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | 添加附件（iOS） |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | 设置 Android 配置 |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | 设置 iOS 配置 |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | 发送通知 |

